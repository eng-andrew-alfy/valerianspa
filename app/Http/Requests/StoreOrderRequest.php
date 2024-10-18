<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreOrderRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         */
        public function authorize(): bool
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
         */
        public function rules(): array
        {
            return [
                //'servicesAvailable' => 'required|in:at_home,at_spa',
                'payment_method' => 'required|in:myfatoorah,tamara',
//                'categories' => 'nullable|exists:categories,id|required_without:packageId',
//                'packageId' => 'nullable|exists:packages,id|required_without:categories',
                'employeeAvailable' => 'required|exists:employees,id',
                'coupon_code' => 'nullable|string|exists:coupons,code',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'notes' => 'nullable|string|max:255',
                'formattedDate' => 'required|date_format:Y-m-d',
                'timeAvailable' => 'required|date_format:g:i A',
            ];

        }

        public function messages()
        {
            return [
//                'categories.required_without' => 'يجب اختيار فئة إذا لم يتم اختيار باقة.',
//                'packageId.required_without' => 'يجب اختيار باقة إذا لم يتم اختيار فئة.',
                'employeeAvailable.required' => 'الرجاء اختيار موظف متاح.',
                'employeeAvailable.exists' => 'الموظف المحدد غير موجود.',
                'coupon_code.exists' => 'كود الخصم غير صالح.',
                'latitude.required' => 'يرجى تحديد الموقع الجغرافي.',
                'latitude.numeric' => 'الموقع الجغرافي غير صحيح.',
                'longitude.required' => 'يرجى تحديد الموقع الجغرافي.',
                'longitude.numeric' => 'الموقع الجغرافي غير صحيح.',
//                'servicesAvailable.required' => 'يجب اختيار مكان الحجز.',
//                'servicesAvailable.in' => 'اختيار مكان الحجز غير صحيح.',
                'timeAvailable.required' => 'يجب اختيار الوقت.',
                'timeAvailable.exists' => 'الوقت المختار غير صحيح.',
                'formattedDate.required' => 'يجب اختيار تاريخ.',
                'formattedDate.date_format' => 'التاريخ المختار غير صحيح. يجب أن يكون بالتنسيق Y-m-d.',
                'payment_method.required' => 'يجب اختيار طريقة الدفع.',
                'payment_method.in' => 'طريقة الدفع المختارة غير صحيحة.',
                'packageId.required' => 'يجب اختيار باقة.',
            ];

        }
    }
