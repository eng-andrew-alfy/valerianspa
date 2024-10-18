<?php

    namespace App\Http\Requests\Dashboard;

    use Illuminate\Foundation\Http\FormRequest;

    class StorePackageRequest extends FormRequest
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
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'time' => 'required|integer|min:25',
                'num_sessions' => 'required|integer|min:1',
                'price_spa' => 'required_without_all:price_home|numeric',
                'price_home' => 'required_without_all:price_spa|numeric',
                'work_location' => 'required|array|min:1',
                'work_location.*' => 'in:home,spa',
                'employee_ids' => 'nullable|array',
                'employee_ids.*' => 'exists:employees,id',
                'description_ar' => 'required|string',
                'description_en' => 'required|string',
                //'inputs_ar' => 'required|array|min:1',
                //'inputs_ar.*' => 'string|max:255',
                //'inputs_en' => 'required|array|min:1',
                // 'inputs_en.*' => 'string|max:255',
            ];
        }

        public function messages()
        {
            return [
                'name_ar.required' => 'يرجى كتابة اسم الباقة بالعربية.',
                'name_ar.string' => 'اسم الباقة بالعربية يجب أن يكون نصياً.',
                'name_ar.max' => 'اسم الباقة بالعربية يجب ألا يتجاوز 255 حرفاً.',

                'name_en.required' => 'يرجى كتابة اسم الباقة بالإنجليزية.',
                'name_en.string' => 'اسم الباقة بالإنجليزية يجب أن يكون نصياً.',
                'name_en.max' => 'اسم الباقة بالإنجليزية يجب ألا يتجاوز 255 حرفاً.',

                'time.required' => 'يرجى كتابة المدة بالدقائق.',
                'time.integer' => 'المدة يجب أن تكون قيمة رقمية صحيحة.',
                'time.min' => 'المدة يجب أن تكون 25 دقيقة على الأقل.',

                'num_sessions.required' => 'يرجى كتابة عدد الجلسات.',
                'num_sessions.integer' => 'عدد الجلسات يجب أن يكون قيمة رقمية صحيحة.',
                'num_sessions.min' => 'عدد الجلسات يجب أن يكون جلسة واحدة على الأقل.',

                'price_spa.required_without_all' => 'يرجى إدخال قيمة في حقل سعر السبا أو حقل سعر المنزل.',
                'price_spa.numeric' => 'يجب أن يكون سعر السبا رقمًا.',

                'price_home.required_without_all' => 'يرجى إدخال قيمة في حقل سعر السبا أو حقل سعر المنزل.',
                'price_home.numeric' => 'يجب أن يكون سعر المنزل رقمًا.',

                'work_location.required' => 'يرجى اختيار على الأقل موقع واحد للخدمة.',
                'work_location.array' => 'الاختيار يجب أن يكون في صورة قائمة.',
                'work_location.*.in' => 'الاختيار يجب أن يكون إما خدمات منزلية أو خدمات داخل الفرع.',

                'employee_ids.array' => 'الاختيار يجب أن يكون في صورة قائمة.',
                'employee_ids.*.exists' => 'الموظف المختار غير موجود.',

                'description_ar.required' => 'يرجى كتابة الوصف بالعربية.',
                'description_ar.string' => 'الوصف بالعربية يجب أن يكون نصياً.',

                'description_en.required' => 'يرجى كتابة الوصف بالإنجليزية.',
                'description_en.string' => 'الوصف بالإنجليزية يجب أن يكون نصياً.',

//                'inputs_ar.required' => 'يرجى كتابة الفوائد بالعربية.',
//                'inputs_ar.array' => 'الفوائد بالعربية يجب أن تكون في صورة قائمة.',
//                'inputs_ar.*.string' => 'كل فائدة بالعربية يجب أن تكون نصاً.',
//                'inputs_ar.*.max' => 'كل فائدة بالعربية يجب ألا تتجاوز 255 حرفاً.',
//
//                'inputs_en.required' => 'يرجى كتابة الفوائد بالإنجليزية.',
//                'inputs_en.array' => 'الفوائد بالإنجليزية يجب أن تكون في صورة قائمة.',
//                'inputs_en.*.string' => 'كل فائدة بالإنجليزية يجب أن تكون نصاً.',
//                'inputs_en.*.max' => 'كل فائدة بالإنجليزية يجب ألا تتجاوز 255 حرفاً.',

                'price_home.gt' => 'يرجى التأكد من أن سعر الخدمة المنزلية أكبر من سعر الخدمة داخل الفرع. هل من المنطقي أن يكون سعر الخدمة المنزلية أقل من الخدمة داخل الفرع؟',
            ];
        }

        protected function withValidator($validator)
        {
            $validator->after(function ($validator) {
                if ($this->input('price_home') <= $this->input('price_spa')) {
                    $validator->errors()->add('price_home', 'يرجى التأكد من أن سعر الخدمة المنزلية أكبر من سعر الخدمة داخل الفرع. هل من المنطقي أن يكون سعر الخدمة المنزلية أقل من الخدمة داخل الفرع؟');
                }
            });
        }
    }
