<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl; /* تعيين اتجاه الكتابة من اليمين لليسار */
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 600px;
            margin: 20px auto;
            border-top: 8px solid #007d96; /* لون رئيسي */
        }

        h1 {
            color: #166f82; /* لون عناوين */
            text-align: center;
            font-size: 26px;
            margin-bottom: 20px;
            font-weight: bold; /* جعل العنوان أكثر بروزًا */
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }

        .highlight {
            color: #51c4d3; /* لون بارز */
            font-weight: bold;
        }

        .logo {
            display: block;
            margin: 0 auto;
            width: 150px; /* تعديل حسب حجم الشعار */
        }

        .order-details {
            background-color: #e8f9fa; /* لون خلفية تفاصيل الطلب */
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #007d96; /* إضافة حدود لتفاصيل الطلب */
        }

        .order-details strong {
            color: #007d96; /* لون نص التفاصيل */
        }

        /* تصميم footer بشكل جذاب */
        .footer {
            border-top: 1px solid #dcdcdc;
            padding-top: 10px;
            margin-top: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="{{asset('front/images/logo/logo-stroke.PNG')}}" alt="Valerian Spa" class="logo">
    <h1>تفاصيل الطلب</h1>
    <p>مرحبًا <span class="highlight">{{ $orderData['customer_name'] }}</span>،</p>
    <p>شكرًا لك على اختيارك <span class="highlight">Valerian Spa</span> لتجربة الاسترخاء المثالية.</p>

    <div class="order-details">
        <p><strong>كود الطلب:</strong> {{ $orderData['code'] }}</p>
        <p><strong>إسم الخدمة او الباقة:</strong> {{ $orderData['service_name'] }}</p>
        <p><strong>المبلغ الإجمالي:</strong> {{ $orderData['total_amount'] }} ر.س</p>
    </div>

    <p>نتطلع لرؤيتك قريبًا! إذا كان لديك أي استفسارات، لا تتردد في الاتصال بنا.</p>

    <div class="footer">
        <p>جميع الحقوق محفوظة © 2024</p>
        <p>تحت إشراف <span class="highlight">Valerian Spa</span></p>
    </div>
</div>

</body>
</html>
