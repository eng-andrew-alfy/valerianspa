<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreSaveGiftOrderRequest extends FormRequest
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
                'employeeAvailable' => 'required|exists:employees,id',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                //'notes' => 'nullable|string|max:255',
                'formattedDate' => 'required|date_format:Y-m-d',
                'timeAvailable' => 'required|date_format:g:i A',
            ];
        }

        public function messages()
        {
            return [
                'employeeAvailable.required' => 'الرجاء اختيار موظف متاح.',
                'employeeAvailable.exists' => 'الموظف المحدد غير موجود.',
                'latitude.required' => 'يرجى تحديد الموقع الجغرافي.',
                'latitude.numeric' => 'الموقع الجغرافي غير صحيح.',
                'longitude.required' => 'يرجى تحديد الموقع الجغرافي.',
                'longitude.numeric' => 'الموقع الجغرافي غير صحيح.',
                'timeAvailable.required' => 'يجب اختيار الوقت.',
                'timeAvailable.exists' => 'الوقت المختار غير صحيح.',
                'formattedDate.required' => 'يجب اختيار تاريخ.',
                'formattedDate.date_format' => 'التاريخ المختار غير صحيح. يجب أن يكون بالتنسيق Y-m-d.',
            ];

        }
    }
