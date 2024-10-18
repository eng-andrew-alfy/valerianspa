<?php

    namespace App\Http\Requests\Dashboard;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreAdminRequest extends FormRequest
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
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|string|min:6|confirmed',
                'roles_name' => 'required|array',
                'roles_name.*' => 'exists:roles,name',
            ];
        }

        public function messages()
        {
            return [
                'name.required' => 'الاسم مطلوب.',
                'email.required' => 'البريد الإلكتروني مطلوب.',
                'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
                'password.required' => 'كلمة المرور مطلوبة.',
                'password.confirmed' => 'كلمة المرور وتأكيد كلمة المرور غير متطابقين.',
                'roles_name.required' => 'يجب تحديد الأدوار.',
                'roles_name.array' => 'الأدوار يجب أن تكون مصفوفة.',
                'roles_name.*.exists' => 'بعض الأدوار المحددة غير موجودة.',
            ];
        }
    }
