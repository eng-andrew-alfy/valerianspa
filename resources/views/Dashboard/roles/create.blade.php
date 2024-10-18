@extends('Dashboard.layouts.master')
@section('css_dashboard')
    <style>
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
            width: 1600px;
            /* تعديل العرض */
        }

        .box {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            grid-gap: 15px;
            width: 100%;
            /* جعل الصندوق يأخذ العرض الكامل */
            height: 450px;
            border: 1px solid #0073aa;
            border-radius: 15px;
            overflow-y: auto;
            padding: 20px;
            background-color: #f7f7f7;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            color: #23282d;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            /* مسافة بين الصندوقين */
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .item {
            padding: 15px;
            background-color: #0073aa;
            color: #ffffff;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s;
            min-width: 120px;
            /* يضمن عدم تقليل عرض العنصر */
        }


        .item:hover {
            background-color: #005f8a;
            transform: scale(1.05);
        }

        .item.selected {
            background-color: #23282d;
            color: #ffffff;
            border: 2px solid #0073aa;
            /* تأثير التحديد */
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            /* مسافة بين الأزرار والصناديق */
        }

        .buttons button {
            padding: 15px;
            font-size: 28px;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0073aa;
            color: #ffffff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            border: none;
            cursor: pointer;
        }

        .buttons button:hover {
            background-color: #005f8a;
            transform: scale(1.15);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
    </style>

@endsection
@section('title_page')
    الصفحة الصلاحيات
@endsection

@section('page-body')

    <div class="card">
        <div class="card-block">
            <h4 class="sub-title">إضافة صلاحية</h4>
            <div class="container">
                <div class="box" id="available" ondrop="drop(event)" ondragover="allowDrop(event)">
                    @foreach ($permission as $permissions)
                        <div class="item" draggable="true" ondragstart="drag(event)"
                             id="permission-{{ $permissions->id }}">
                            <span>{{ $permissions->description }}</span>
                            <i class="fas fa-user"></i>
                        </div>
                    @endforeach
                </div>

                <div class="buttons">
                    <button class="btn" onclick="moveRight()">></button>
                    <button class="btn" onclick="moveLeft()">
                        <
                    </button>
                </div>
            </div>

            <form method="post" action="{{ route('admin.roles.store') }}">
                @csrf
                <div class="box" id="selected" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <!-- الصلاحيات المختارة ستظهر هنا -->
                </div>
                <input type="hidden" id="permissions" name="permissions[]">
                <div class="col-sm-12 col-xl-4 m-b-30">
                    <p><code>*</code> اسم الصلاحية</p>
                    <input type="text" name="name" id="name" class="form-control"
                           value="" required>
                    <br>
                    @error('code')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        function allowDrop(event) {
            event.preventDefault();
        }

        function drag(event) {
            event.dataTransfer.setData("text", event.target.id);
        }

        function drop(event) {
            event.preventDefault();
            const data = event.dataTransfer.getData("text");
            const item = document.getElementById(data);
            if (event.target.classList.contains('box')) {
                event.target.appendChild(item);
                updateHiddenInputs();
            }
        }

        function moveRight() {
            const available = document.getElementById('available');
            const selected = document.getElementById('selected');
            moveSelectedItems(available, selected);
            updateHiddenInputs();
        }

        function moveLeft() {
            const available = document.getElementById('available');
            const selected = document.getElementById('selected');
            moveSelectedItems(selected, available);
            updateHiddenInputs();
        }

        function moveSelectedItems(fromBox, toBox) {
            const selectedItems = fromBox.querySelectorAll('.item.selected');
            selectedItems.forEach(item => {
                toBox.appendChild(item);
                item.classList.remove('selected');
            });
        }

        document.querySelectorAll('.item').forEach(item => {
            item.addEventListener('click', function () {
                this.classList.toggle('selected');
            });
        });

        function updateHiddenInputs() {
            const selectedBox = document.getElementById('selected');
            const permissionsInput = document.getElementById('permissions');

            // الحصول على المعرفات من العناصر في الصندوق المختار
            const ids = Array.from(selectedBox.querySelectorAll('.item')).map(item => item.id.replace('permission-', ''));

            // تعيين القيم للإدخال المخفي
            permissionsInput.value = ids.join(',');

            // أضف الإدخالات المخفية الجديدة بناءً على العناصر الموجودة في الصندوق المختار
            const form = document.querySelector('form');
            form.querySelectorAll('input[type="hidden"]').forEach(input => {
                if (input.id !== 'permissions') input.remove();
            });

            ids.forEach(id => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'permissions[]';
                input.value = id;
                form.appendChild(input);
            });
        }
    </script>
@endsection
