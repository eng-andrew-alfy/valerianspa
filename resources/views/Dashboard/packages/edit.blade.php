@extends('Dashboard.layouts.master')
{{-- @section('title_dashboard') --}}
{{-- @endsection --}}
@section('css_dashboard')
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/assets/pages/advance-elements/css/bootstrap-datetimepicker.css') }}" />
    <!-- Date-range picker css  -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/assets/bootstrap-daterangepicker/css/daterangepicker.css') }}" />
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets//datedropper/css/datedropper.min.css') }}" />
    <!-- Color Picker css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/spectrum/css/spectrum.css') }}" />
    <!-- Mini-color css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/assets/pages/jquery-minicolors/css/jquery.minicolors.css') }}" />
    <!-- Switch component css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/switchery/css/switchery.min.css') }}">

    <style>
        .tags-input-container {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid rgba(0, 0, 0, .15);
            padding: 5px;
            border-radius: 5px;
            min-height: 40px;
            width: 100%;
            overflow: hidden;
            box-sizing: border-box;
            position: relative;
            align-items: center;
            /* Align items to center for better spacing */
        }

        .tags-input-container input {
            border: none;
            outline: none;
            padding: 5px;
            font-size: 14px;
            min-width: 100px;
            width: 100%;
            /* Full width to adjust dynamically */
            box-sizing: border-box;
            flex: 1;
            resize: none;
            /* Prevent manual resizing */
        }

        .tag {
            background-color: #0a6aa1;
            color: white;
            padding: 5px 10px;
            margin: 3px;
            border-radius: 3px;
            display: flex;
            align-items: center;
            white-space: nowrap;
            overflow: hidden;
            /* Prevent text from overflowing */
        }

        .tag .remove-tag {
            margin-right: 3px;
            margin-left: 3px;
            cursor: pointer;

            position: relative;
            top: -0.4em;

        }
    </style>
@endsection
@section('title_page')
    تعديل الباقة
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
@endsection

@section('page-body')
    <div class="card">
        <div class="card-block">
            <form id="addPlaceForm" action="{{ route('admin.AllPackages.update', $package->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> اسم الباقة بالعربية </p>
                        <input type="text" name="name_ar" id="name_ar" class="form-control"
                            placeholder="يرجى كتابة اسم الباقة بالعربية"
                            value="{{ $package->getTranslation('name', 'ar') }}" required>
                        <br>
                        @error('name_ar')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> اسم الباقة بالانجليزية </p>
                        <input type="text" name="name_en" id="name_en" class="form-control"
                            placeholder="يرجى كتابة اسم الباقة بالانجليزية"
                            value="{{ $package->getTranslation('name', 'en') }}" required>
                        <br>
                        @error('name_en')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> مدة بالدقائق</p>
                        <input type="text" class="form-control" name="time" placeholder="يرجى كتابة المدة بالدقائق"
                            pattern="\d+" title="يرجى إدخال أرقام فقط" value="{{ $package->duration_minutes }}" required>
                        <br>
                        @error('time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-3 m-b-30">
                        <p><code>*</code> عدد الجلسات </p>
                        <input type="text" name="num_sessions" class="form-control" placeholder="يرجى كتابة عدد الجلسات"
                            pattern="\d+" title="يرجى إدخال أرقام فقط" value="{{ $package->sessions_count }}" required>
                        <br>
                        @error('num_sessions')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> السعر الفرع</p>
                        <input type="text" name="price_spa" class="form-control"
                            placeholder="يرجى كتابة السعر المخصص للخدمة بداخل الفرع" pattern="\d+"
                            title="يرجى إدخال أرقام فقط" value="{{ number_format($package->prices->at_spa, 0, '.', '') }}"
                            required>
                        <br>
                        @error('price_spa')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> سعر المنازل</p>
                        <input type="text" name="price_home" class="form-control"
                            placeholder="يرجى كتابة السعر المخصص للخدمة المنزلية" pattern="\d+"
                            title="يرجى إدخال أرقام فقط" value="{{ number_format($package->prices->at_home, 0, '.', '') }}"
                            required>
                        <br>
                        @error('price_home')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> الخدمات </p>
                        <select class="js-example-rtl col-sm-12" name="work_location[]" multiple="multiple">
                            @foreach ($work_locations as $key => $label)
                                <option value="{{ $key }}" @if ($package->availability->isAvailable($key)) selected @endif>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>


                        <br>
                        @error('work_location')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>الموظفين<code>( يرجى اختيار الخدمة أولاً )</code></p>
                        <select class="js-example-rtl col-sm-12" multiple="multiple" style="width: 75%"
                            name="employee_ids[]">
                            <optgroup label="الموظفين المرتبطين بالباقة">
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" @if ($package->employees->contains($employee->id)) selected @endif>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>

                        <br>
                        @error('employee_ids')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>الوصف عربى </p>
                        <textarea class="form-control" name="description_ar" id="description_ar" rows="3" placeholder="الوصف عربى">{{ $package->getTranslation('description', 'ar') }}</textarea>
                        <br>
                        @error('description_ar')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>الوصف انجليزي </p>
                        <textarea class="form-control" name="description_en" id="description_en" rows="3"
                            placeholder="الوصف انجليزي">{{ $package->getTranslation('description', 'en') }}</textarea>
                        <br>
                        @error('description_en')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-12 col-xl-4 m-b-30">
                            <p><code>*</code> الفوائد عربى<code>قم بالضغط على علامة + لزيادة فائدة جديدة</code></p>
                            <div id="input-container-ar">
                                @foreach ($benefits_ar as $index => $benefit)
                                    <div class="input-group mb-3">
                                        <input type="text" name="inputs_ar[]" class="form-control"
                                            placeholder="يرجى كتابة الفوائد بالعربية"
                                            value="{{ old('inputs_ar.' . $index, $benefit) }}">
                                    </div>
                                @endforeach

                            </div>
                            <br>
                            @error('inputs_ar')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12 col-xl-4 m-b-30">
                            <p><code>*</code> الفوائد إنجليزي<code>قم بالضغط على علامة + لزيادة فائدة جديدة</code></p>
                            <div id="input-container-en">
                                <!-- عرض الفوائد الإنجليزية -->
                                @foreach ($benefits_en as $index => $benefit)
                                    <div class="input-group mb-3">
                                        <input type="text" name="inputs_en[]" class="form-control"
                                            placeholder="يرجى كتابة الفوائد بالانجليزية"
                                            value="{{ old('inputs_en.' . $index, $benefit) }}">
                                    </div>
                                @endforeach

                            </div>
                            <br>
                            @error('inputs_en')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12 col-xl-4 m-b-30 d-flex align-items-start">
                            <div>
                                <button class="btn btn-primary" type="button" onclick="addInput()">+</button>
                                <button class="btn btn-default" type="button" onclick="removeInput()">-</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="display: block; margin: 0 auto;">حفظ الباقة <i
                        class="icon-user-following"></i></button>
            </form>

        </div>
    </div>
@endsection

@section('script_dashboard')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/select2/js/select2.full.min.js') }}"></script>
    <!-- Multiselect js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/bootstrap-multiselect/js/bootstrap-multiselect.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/multiselect/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/jquery.quicksearch.js') }}"></script>
    <!-- Masking js -->
    <script src="{{ asset('dashboard/assets/pages/form-masking/inputmask.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/autoNumeric.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/form-mask.js') }}"></script>
    <!-- Bootstrap date-time-picker js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/pages/advance-elements/moment-with-locales.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ asset('dashboard/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('dashboard/assets/pages/advance-elements/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Date-range picker js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/bootstrap-daterangepicker/js/daterangepicker.js') }}">
    </script>
    <!-- Date-dropper js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/datedropper/js/datedropper.min.js') }}"></script>
    <!-- Color picker js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/spectrum/js/spectrum.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/jscolor/js/jscolor.js') }}"></script>
    <!-- Mini-color js -->
    <script type="text/javascript"
        src="{{ asset('dashboard/assets/pages/jquery-minicolors/js/jquery.minicolors.min.js') }}"></script>
    <!-- Custom js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/pages/advance-elements/custom-picker.js') }}"></script>

    <!-- Max-length js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/bootstrap-maxlength/js/bootstrap-maxlength.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/pages/advance-elements/swithces.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to handle the tag input for a specific container and input field
            function handleTagsInput(tagsInputContainerId, tagsInputId, hiddenInputName) {
                const tagsInputContainer = document.getElementById(tagsInputContainerId);
                const tagsInput = document.getElementById(tagsInputId);
                const hiddenTagsInput = document.createElement('input');
                hiddenTagsInput.type = 'hidden';
                hiddenTagsInput.name = hiddenInputName;
                tagsInputContainer.appendChild(hiddenTagsInput);
                const tags = [];

                tagsInput.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        const tagText = tagsInput.value.trim();

                        if (tagText !== "" && !tags.includes(tagText)) {
                            tags.push(tagText);
                            const tagElement = document.createElement('span');
                            tagElement.classList.add('tag');
                            tagElement.textContent = tagText;

                            const removeButton = document.createElement('span');
                            removeButton.classList.add('remove-tag');
                            removeButton.textContent = 'x';
                            removeButton.addEventListener('click', function() {
                                tagsInputContainer.removeChild(tagElement);
                                const index = tags.indexOf(tagText);
                                if (index > -1) {
                                    tags.splice(index, 1);
                                }
                                updateHiddenTagsInput();
                            });

                            tagElement.appendChild(removeButton);
                            tagsInputContainer.insertBefore(tagElement, tagsInput);
                            tagsInput.value = '';
                            tagsInput.focus();
                            updateHiddenTagsInput();
                        }
                    }
                });

                tagsInput.addEventListener('input', function() {
                    tagsInput.style.height = 'auto';
                    tagsInput.style.height = tagsInput.scrollHeight + 'px';
                });

                function updateHiddenTagsInput() {
                    hiddenTagsInput.value = tags.join(','); // Separate tags with a comma
                }
            }

            // Apply the function to both inputs
            handleTagsInput('tags-input-container-ar', 'tags-input-ar', 'tags_ar');
            handleTagsInput('tags-input-container-en', 'tags-input-en', 'tags_en');
        });


        //**********************************   START GET EMPLOYEES FROM PACKAGES   **********************************//
        {{-- $(document).ready(function () { --}}
        {{--    // Disable employee select on page load --}}
        {{--    $('select[name="employee_ids[]"]').prop('disabled', true); --}}

        {{--    $('.js-example-rtl[name="work_location[]"]').on('change', function () { --}}
        {{--        var workLocations = $(this).val(); --}}
        {{--        var employeeSelect = $('select[name="employee_ids[]"]'); --}}

        {{--        if (workLocations.length > 0) { --}}
        {{--            // Enable employee select if service is selected --}}
        {{--            employeeSelect.prop('disabled', false); --}}

        {{--            $.ajax({ --}}
        {{--                url: '{{ route("admin.packages.fetchEmployeesOfPackage") }}', --}}
        {{--                method: 'GET', --}}
        {{--                data: { --}}
        {{--                    work_locations: workLocations --}}
        {{--                }, --}}
        {{--                success: function (response) { --}}
        {{--                    employeeSelect.empty(); // Clear previous options --}}

        {{--                    if (response.length > 0) { --}}
        {{--                        $.each(response, function (index, employee) { --}}
        {{--                            employeeSelect.append('<option value="' + employee.id + '">' + employee.name + '</option>'); --}}
        {{--                        }); --}}
        {{--                    } else { --}}
        {{--                        employeeSelect.append('<option value="" disabled>لا يوجد موظفين متاحين</option>'); --}}
        {{--                    } --}}
        {{--                } --}}
        {{--            }); --}}
        {{--        } else { --}}
        {{--            // Disable employee select if no service is selected --}}
        {{--            employeeSelect.prop('disabled', true).empty().append('<option value="">يرجى اختيار الخدمة أولاً</option>'); --}}
        {{--        } --}}
        {{--    }); --}}
        {{-- }); --}}

        //**********************************   END GET EMPLOYEES FROM PACKAGES   **********************************//

        //**********************************   START REPEAT INPUTS BENEFIT FROM PACKAGES   **********************************//

        // Function to add a new input group for both Arabic and English inputs
        function addInput() {
            var containerAr = document.getElementById('input-container-ar');
            var containerEn = document.getElementById('input-container-en');

            // Create a new input group for Arabic
            var newInputGroupAr = document.createElement('div');
            newInputGroupAr.classList.add('input-group', 'mb-3');

            // Arabic Input Field
            var inputFieldAr = document.createElement('input');
            inputFieldAr.type = 'text';
            inputFieldAr.name = 'inputs_ar[]';
            inputFieldAr.classList.add('form-control');
            inputFieldAr.placeholder = 'يرجى كتابة الفوائد بالعربية';

            // Append Arabic input field to the new input group
            newInputGroupAr.appendChild(inputFieldAr);

            // Create a new input group for English
            var newInputGroupEn = document.createElement('div');
            newInputGroupEn.classList.add('input-group', 'mb-3');

            // English Input Field
            var inputFieldEn = document.createElement('input');
            inputFieldEn.type = 'text';
            inputFieldEn.name = 'inputs_en[]';
            inputFieldEn.classList.add('form-control');
            inputFieldEn.placeholder = 'يرجى كتابة الفوائد بالانجليزية';

            // Append English input field to the new input group
            newInputGroupEn.appendChild(inputFieldEn);

            // Append the new input groups to their respective containers
            containerAr.appendChild(newInputGroupAr);
            containerEn.appendChild(newInputGroupEn);

            // Update the state of the remove button
            updateRemoveButtons();
        }

        // Function to remove the last input group for both Arabic and English inputs
        function removeInput() {
            var containerAr = document.getElementById('input-container-ar');
            var containerEn = document.getElementById('input-container-en');

            // Remove the last input group for Arabic if available
            if (containerAr.children.length > 1) {
                containerAr.removeChild(containerAr.lastChild);
            }

            // Remove the last input group for English if available
            if (containerEn.children.length > 1) {
                containerEn.removeChild(containerEn.lastChild);
            }

            // Update the state of the remove button
            updateRemoveButtons();
        }

        // Function to update the state of the remove button (disable if only one input group remains)
        function updateRemoveButtons() {
            var containerAr = document.getElementById('input-container-ar');
            var containerEn = document.getElementById('input-container-en');

            // Enable or disable remove button based on the number of input groups
            var removeButton = document.querySelector('button.btn-danger');
            if (containerAr.children.length <= 1 && containerEn.children.length <= 1) {
                removeButton.disabled = true;
            } else {
                removeButton.disabled = false;
            }
        }

        // Ensure the remove button is in the correct state on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });
        //**********************************   END REPEAT INPUTS BENEFIT FROM PACKAGES   **********************************//
    </script>
@endsection
