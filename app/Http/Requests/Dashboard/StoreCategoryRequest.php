<?php

    namespace App\Http\Requests\Dashboard;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreCategoryRequest extends FormRequest
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
                'name_ar' => 'required|string|regex:/^[\p{Arabic}\s]+$/u',
                'name_en' => 'required|string|regex:/^[a-zA-Z\s]+$/',
                'description_ar' => 'required|string|regex:/^[\p{Arabic}\s]+$/u',
                'description_en' => 'required|string|regex:/^[a-zA-Z\s]+$/',
                'time' => 'required|integer|min:25',
                'num_sessions' => 'required|integer|min:1',
                'price_spa' => 'required_without_all:price_home|numeric',
                'price_home' => 'required_without_all:price_spa|numeric',
                'work_location' => 'required|array',
                'employee_ids' => 'nullable|array',
                'services' => 'nullable',
                //'inputs_ar.*' => 'nullable|array',
                //'inputs_en.*' => 'nullable|array',
            ];
        }

        public function messages()
        {
            return [
                'name_ar.required' => 'يرجى إدخال اسم الخدمة بالعربية.',
                'name_ar.string' => 'اسم الخدمة بالعربية يجب أن يكون نصاً.',
                'name_ar.regex' => 'اسم الخدمة بالعربية يمكن أن يحتوي فقط على أحرف عربية ومسافات.',

                'name_en.required' => 'يرجى إدخال اسم الخدمة بالإنجليزية.',
                'name_en.string' => 'اسم الخدمة بالإنجليزية يجب أن يكون نصاً.',
                'name_en.regex' => 'اسم الخدمة بالإنجليزية يمكن أن يحتوي فقط على أحرف إنجليزية ومسافات.',

                'description_ar.required' => 'يرجى إدخال الوصف بالعربية.',
                'description_ar.string' => 'الوصف بالعربية يجب أن يكون نصاً.',
                'description_ar.regex' => 'الوصف بالعربية يمكن أن يحتوي فقط على أحرف عربية ومسافات.',

                'description_en.required' => 'يرجى إدخال الوصف بالإنجليزية.',
                'description_en.string' => 'الوصف بالإنجليزية يجب أن يكون نصاً.',
                'description_en.regex' => 'الوصف بالإنجليزية يمكن أن يحتوي فقط على أحرف إنجليزية ومسافات.',

                'time.required' => 'يرجى إدخال مدة الخدمة بالدقائق.',
                'time.integer' => 'مدة الخدمة بالدقائق يجب أن تكون رقماً صحيحاً.',
                'time.min' => 'مدة الخدمة بالدقائق يجب أن تكون على الأقل 25 دقيقة.',

                'num_sessions.required' => 'يرجى إدخال عدد الجلسات.',
                'num_sessions.integer' => 'عدد الجلسات يجب أن يكون رقماً صحيحاً.',
                'num_sessions.min' => 'عدد الجلسات يجب أن يكون على الأقل 1.',

                'price_spa.required_without_all' => 'يرجى إدخال قيمة في حقل سعر السبا أو حقل سعر المنزل.',
                'price_spa.numeric' => 'يجب أن يكون سعر السبا رقمًا.',

                'price_home.required_without_all' => 'يرجى إدخال قيمة في حقل سعر السبا أو حقل سعر المنزل.',
                'price_home.numeric' => 'يجب أن يكون سعر المنزل رقمًا.',

                'work_location.required' => 'يرجى تحديد مواقع العمل.',
                'work_location.array' => 'مواقع العمل يجب أن تكون مصفوفة.',

                'employee_ids.array' => 'موظفون يجب أن يكون مصفوفة.',
                'services.required' => 'يرجى تحديد الخدمة.',

                // 'inputs_ar.*.string' => 'كل فائدة بالعربية يجب أن تكون نصاً.',
                //'inputs_ar.*.regex' => 'كل فائدة بالعربية يمكن أن تحتوي فقط على أحرف عربية ومسافات.',

                //'inputs_en.*.string' => 'كل فائدة بالإنجليزية يجب أن تكون نصاً.',
                //'inputs_en.*.regex' => 'كل فائدة بالإنجليزية يمكن أن تحتوي فقط على أحرف إنجليزية ومسافات.',
            ];
        }

    }
