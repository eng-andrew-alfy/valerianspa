<?php

    namespace App\Http\Requests\Dashboard;

    use Illuminate\Foundation\Http\FormRequest;

    class UpdateCouponRequest extends FormRequest
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
                'code' => 'required|string|max:255',
                'discount_type' => 'required|in:fixed,percentage',
                'value' => 'required|numeric|min:0',
                'usage_limit' => 'nullable|integer',
                'coupon_type' => 'required|in:infinite,temporary',
                'daterange' => 'nullable|string',
                'categories' => 'nullable|array',
                'packages' => 'nullable|array',
            ];
        }

        public function messages(): array
        {
            return [
                'code.required' => 'رمز الكوبون مطلوب',
                'code.string' => 'رمز الكوبون يجب أن يكون نصًا',
                'code.max' => 'رمز الكوبون لا يجب أن يتجاوز 255 حرفًا',
                'discount_type.required' => 'نوع الخصم مطلوب',
                'discount_type.in' => 'نوع الخصم يجب أن يكون إما "ثابت" أو "نسبة"',
                'value.required' => 'قيمة الخصم مطلوبة',
                'value.numeric' => 'قيمة الخصم يجب أن تكون عددًا',
                'value.min' => 'قيمة الخصم يجب أن تكون على الأقل 0',
                'coupon_type.required' => 'نوع الكوبون مطلوب',
                'coupon_type.in' => 'نوع الكوبون يجب أن يكون إما "غير محدود" أو "مؤقت"',
                'daterange.string' => 'نطاق التواريخ يجب أن يكون نصًا',
                'categories.array' => 'الفئات يجب أن تكون مصفوفة',
                'packages.array' => 'الباقات يجب أن تكون مصفوفة',
            ];


        }
    }
