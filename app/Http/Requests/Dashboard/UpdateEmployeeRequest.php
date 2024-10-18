<?php

    namespace App\Http\Requests\Dashboard;

    use Illuminate\Foundation\Http\FormRequest;

    class UpdateEmployeeRequest extends FormRequest
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
                'employee_code' => 'required|string|max:255|unique:employees,code,' . $this->route('employee'),
                'email' => 'required|email|max:255|unique:employees,email,' . $this->route('employee'),
                'phone' => 'required|string|max:20',
                'national_id' => 'required|string|max:255',
                'employee_nationality' => 'required|string|max:255',
                'work_location' => 'required|string|max:255',
                'employee_color' => 'required|string|max:7',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'place_ids' => 'array',
                'working_days' => 'array',
            ];

        }

        public function messages(): array
        {
            return [


                'employee_code.required' => 'رمز الموظف مطلوب.',
                'employee_code.string' => 'رمز الموظف يجب أن يكون نصًا.',
                'employee_code.max' => 'رمز الموظف يجب ألا يتجاوز 255 حرفًا.',
                'employee_code.unique' => 'رمز الموظف هذا مستخدم بالفعل.',

                'email.required' => 'البريد الإلكتروني مطلوب.',
                'email.email' => 'البريد الإلكتروني غير صالح.',
                'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفًا.',
                'email.unique' => 'البريد الإلكتروني هذا مستخدم بالفعل.',

                'phone.required' => 'رقم الهاتف مطلوب.',
                'phone.string' => 'رقم الهاتف يجب أن يكون نصًا.',
                'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرفًا.',

                'national_id.required' => 'رقم الهوية الوطنية مطلوب.',
                'national_id.string' => 'رقم الهوية الوطنية يجب أن يكون نصًا.',
                'national_id.max' => 'رقم الهوية الوطنية يجب ألا يتجاوز 255 حرفًا.',

                'employee_nationality.required' => 'جنسية الموظف مطلوبة.',
                'employee_nationality.string' => 'جنسية الموظف يجب أن تكون نصًا.',
                'employee_nationality.max' => 'جنسية الموظف يجب ألا تتجاوز 255 حرفًا.',

                'work_location.required' => 'موقع العمل مطلوب.',
                'work_location.string' => 'موقع العمل يجب أن يكون نصًا.',
                'work_location.max' => 'موقع العمل يجب ألا يتجاوز 255 حرفًا.',

                'employee_color.required' => 'لون الموظف مطلوب.',
                'employee_color.string' => 'لون الموظف يجب أن يكون نصًا.',
                'employee_color.max' => 'لون الموظف يجب ألا يتجاوز 7 أحرف.',

                'start_time.required' => 'وقت البدء مطلوب.',
                'start_time.date_format' => 'وقت البدء يجب أن يكون بصيغة HH:mm.',

                'end_time.required' => 'وقت الانتهاء مطلوب.',
                'end_time.date_format' => 'وقت الانتهاء يجب أن يكون بصيغة HH:mm.',

                'place_ids.array' => 'أماكن العمل يجب أن تكون مصفوفة.',

                'working_days.array' => 'أيام العمل يجب أن تكون مصفوفة.',
            ];

        }

    }
