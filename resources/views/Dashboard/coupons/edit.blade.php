@extends('Dashboard.layouts.master')

@section('css_dashboard')
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/pages/advance-elements/css/bootstrap-datetimepicker.css') }}"/>
    <!-- Date-range picker css  -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/bootstrap-daterangepicker/css/daterangepicker.css') }}"/>
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets//datedropper/css/datedropper.min.css') }}"/>
    <!-- Color Picker css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/spectrum/css/spectrum.css') }}"/>
    <!-- Mini-color css -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/pages/jquery-minicolors/css/jquery.minicolors.css') }}"/>
    <!-- Switch component css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/switchery/css/switchery.min.css') }}">
@endsection

@section('title_page')
    تعديل كوبون
@endsection

@section('page-body')
    <div class="card">
        <div class="card-block">
            <form id="editCouponForm" action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Code -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> كود الكوبون </p>
                        <input type="text" name="code" id="code" class="form-control"
                               placeholder="يرجى إدخال كود الكوبون" value="{{ old('code', $coupon->code) }}" required>
                        <br>
                        @error('code')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> نوع الخصم </p>
                        <select name="discount_type" id="discount_type" class="js-example-rtl col-sm-12" required>
                            <option disabled>إختار نوع الخصم</option>
                            <option value="fixed"
                                    @if (old('discount_type', $coupon->discount_type) == 'fixed') selected @endif>خصم
                                ثابت
                            </option>
                            <option value="percentage"
                                    @if (old('discount_type', $coupon->discount_type) == 'percentage') selected @endif>
                                خصم نسبي
                            </option>
                        </select>
                        <br>
                        @error('discount_type')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Value -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> قيمة الخصم </p>
                        <input type="text" name="value" id="value" class="form-control"
                               placeholder="يرجى إدخال قيمة الخصم" pattern="\d+(\.\d{1,2})?"
                               title="يرجى إدخال قيمة صحيحة"
                               value="{{ old('value', $coupon->value) }}" required>
                        <br>
                        @error('value')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Usage Limit -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> حد الاستخدام </p>
                        <input type="text" name="usage_limit" id="usage_limit" class="form-control"
                               placeholder="يرجى إدخال حد الاستخدام" pattern="\d*" title="يرجى إدخال أرقام فقط"
                               value="{{ old('usage_limit', $coupon->usage_limit) }}">
                        <br>
                        @error('usage_limit')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Coupon Type -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> نوع الكوبون</p>
                        <select name="coupon_type" id="coupon_type" class="js-example-rtl col-sm-12" required>
                            <option disabled>إختار نوع الكوبون</option>
                            <option value="infinite"
                                    @if (old('coupon_type', $coupon->coupon_type) == 'infinite') selected @endif>لا
                                نهائى
                            </option>
                            <option value="temporary"
                                    @if (old('coupon_type', $coupon->coupon_type) == 'temporary') selected @endif>مؤقت
                            </option>
                        </select>
                        <br>
                        @error('coupon_type')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p>تاريخ البداية و النهاية </p>
                        <input type="text" name="daterange" class="form-control" id="daterange"
                               value="{{ old('daterange', $startDate ? $startDate->format('m/d/Y') . ' - ' . $endDate->format('m/d/Y') : '') }}"/>
                        <br>

                        @error('daterange')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Categories -->
                    <div class="col-sm-12 col-xl-6 m-b-30">
                        <p>الفئات </p>
                        <select class="js-example-rtl col-sm-12" name="categories[]" multiple>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if (in_array($category->id, old('categories', $coupon->categories->pluck('id')->toArray()))) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <br>
                        @error('categories')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Packages -->
                    <div class="col-sm-12 col-xl-6 m-b-30">
                        <p>الباقات </p>
                        <select class="js-example-rtl col-sm-12" name="packages[]" multiple>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}"
                                        @if (in_array($package->id, old('packages', $coupon->packages->pluck('id')->toArray()))) selected @endif>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>
                        <br>
                        @error('packages')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="display: block; margin: 0 auto;">تعديل كوبون <i
                        class="icon-user-following"></i></button>
            </form>
        </div>
    </div>
@endsection

@section('script_dashboard')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/select2/js/select2.full.min.js') }}"></script>
    <!-- Multiselect js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-multiselect/js/bootstrap-multiselect.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/multiselect/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/jquery.quicksearch.js') }}"></script>
    <!-- Masking js -->
    <script src="{{ asset('dashboard/assets/pages/form-masking/inputmask.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/autoNumeric.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/form-mask.js') }}"></script>
    <!-- Bootstrap date-time-picker js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/advance-elements/moment-with-locales.min.js') }}">
    </script>
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/advance-elements/bootstrap-datetimepicker.min.js') }}">
    </script>
    <!-- Bootstrap date-range-picker js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-daterangepicker/js/moment.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-daterangepicker/js/daterangepicker.js') }}"></script>
    <!-- Date-Dropper js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/datedropper/js/datedropper.min.js') }}"></script>
    <!-- Color Picker js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/spectrum/js/spectrum.js') }}"></script>
    <!-- Mini-color js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/jquery-minicolors/js/jquery.minicolors.min.js') }}"></script>
    <!-- Switch component js -->
    <script src="{{ asset('dashboard/assets/switchery/js/switchery.min.js') }}"></script>
    <!-- Custom js -->
    <script src="{{ asset('dashboard/assets/pages/form-elements.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/script.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            const $coupon = $('#coupon_type');
            const $dateRange = $('#daterange');

            // Function to format the date as 'YYYY-MM-DD'
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed in JavaScript
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            // Function to update the date range field
            function updateDateRange() {
                if ($coupon.val() === 'infinite') {
                    $dateRange.prop('disabled', true); // Disable the field
                    $dateRange.val(''); // Clear the field value
                } else {
                    $dateRange.prop('disabled', false); // Enable the field

                    // Get the current date
                    const currentDate = new Date();
                    // Add 10 days to the current date
                    const endDate = new Date();
                    endDate.setDate(currentDate.getDate() + 10);
                    const to = '-';
                    // Generate the result in the format 'YYYY-MM-DD to YYYY-MM-DD'
                    const result = `${formatDate(currentDate)} ${to} ${formatDate(endDate)}`;

                    // Set the field value
                    $dateRange.val(result);
                }
            }

            // Update the status when the page loads
            updateDateRange();

            // Listen for changes in the coupon type selection
            $coupon.on('change', function () {
                updateDateRange();
            });
        });

    </script>
@endsection
