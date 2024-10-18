<?php

    namespace App\Http\Requests\Dashboard;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreOrderDashboardRequest extends FormRequest
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
                'order_code' => 'required|string|max:255',
                'client_status' => 'required|in:new,existing',
                'existing_client_code' => 'nullable|string|max:255',
                'client_code' => 'nullable|string|max:255',
                'client_name' => 'nullable|string|max:255',
                'client_phone' => 'nullable|numeric',
                'address' => 'nullable|string|max:255',
                'booking_type' => 'required|in:package,category',
                'reservation_status' => 'required|in:at_spa,at_home',
                'categories' => 'nullable|exists:categories,id',
                'packages' => 'nullable|exists:packages,id',
                'date' => 'required|date',
                'time_available' => 'nullable|string',
                'employee_available' => 'nullable|string',
                'payment_status' => 'required|in:pending,paid',
                'payment_type' => 'nullable|required_if:payment_status,paid|in:cash,bank_transfer',
                'notes' => 'nullable|string',
            ];
        }

        public function messages()
        {
            return [
                'order_code.required' => 'كود الطلب مطلوب',
                'client_status.required' => 'حالة العميل مطلوبة',
                'client_code.required' => 'كود العميل مطلوب',
                'client_name.required' => 'إسم العميل مطلوب',
                'client_phone.required' => 'جوال العميل مطلوب',
                'address.required' => 'العنوان مطلوب',
                'booking_type.required' => 'نوع الحجز مطلوب',
                'reservation_status.required' => 'يرجى تحديد حالة الحجز.',
                'reservation_status.in' => 'حالة الحجز يجب أن تكون واحدة من: "بداخل الفرع" أو "منزلى".',
                'date.required' => 'تاريخ الحجز مطلوب',
                'payment_status.required' => 'حالة الدفع مطلوبة',
                'payment_type.required_if' => 'نوع الدفع مطلوب إذا كانت الحالة "مدفوع"',
            ];
        }
    }
