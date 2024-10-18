<?php

    namespace App\Http\Requests\Dashboard;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreEmployeeRequest extends FormRequest
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
                'name' => 'required|array',
                'name.en' => 'required|string|max:255',
                'name.ar' => 'required|string|max:255',
                'code' => 'required|integer|unique:employees,code',
                'email' => 'required|email|unique:employees,email|max:255',
                'phone' => 'required|string|max:20',
                'identity_card' => 'required|integer|unique:employees,identity_card',
                'country' => 'required|string|max:255',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'working_days' => 'required|array',
            ];
        }

        /**
         * تخصيص رسائل الخطأ (اختياري).
         */
        public function messages(): array
        {
            return [
                'name.required' => 'حقل الاسم مطلوب.',
                'name.en.required' => 'الاسم بالإنجليزية مطلوب.',
                'name.ar.required' => 'الاسم بالعربية مطلوب.',
                'code.required' => 'حقل الكود مطلوب.',
                'email.required' => 'البريد الإلكتروني مطلوب.',
                // إضافة رسائل مخصصة أخرى حسب الحاجة
            ];
        }
    }
