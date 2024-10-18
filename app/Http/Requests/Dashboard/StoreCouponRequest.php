<?php

    namespace App\Http\Requests\Dashboard;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreCouponRequest extends FormRequest
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
                'code' => 'required|string|unique:coupons,code',
                'discount_type' => 'required|in:fixed,percentage',
                'coupon_type' => 'required|in:infinite,temporary',
                'value' => 'required|numeric',
                'usage_limit' => 'nullable|integer',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'is_active' => 'boolean',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                'packages' => 'nullable|array',
                'packages.*' => 'exists:packages,id',
            ];
        }

        public function messages(): array
        {
            return [
                'code.required' => 'يرجى إدخال الكود',
                'code.string' => 'يجب أن يكون الكود نصًا',
                'code.unique' => 'هذا الكود موجود بالفعل',
                'type.required' => 'يرجى تحديد نوع الكوبون',
                'type.in' => 'نوع الكوبون يجب أن يكون "ثابت" أو "نسبة مئوية"',
                'value.required' => 'يرجى إدخال قيمة الكوبون',
                'value.numeric' => 'يجب أن تكون القيمة رقمية',
                'usage_limit.integer' => 'يجب أن يكون حد الاستخدام عدد صحيح',
                'start_date.date' => 'يرجى إدخال تاريخ بداية صحيح',
                'end_date.date' => 'يرجى إدخال تاريخ نهاية صحيح',
                'is_active.boolean' => 'يجب أن يكون الحالة نشط/غير نشط',
                'categories.array' => 'يجب أن تكون الفئات مصفوفة',
                'categories.*.exists' => 'إحدى الفئات غير موجودة',
                'packages.array' => 'يجب أن تكون الباقات مصفوفة',
                'packages.*.exists' => 'إحدى الباقات غير موجودة',
            ];
        }
    }
