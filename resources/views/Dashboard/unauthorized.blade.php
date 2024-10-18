<!-- resources/views/errors/unauthorized.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Unauthorized</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container text-center">
    <h1>403 - Unauthorized</h1>
    <p>عذرًا، ليس لديك الصلاحية للوصول إلى هذه الصفحة.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary">الرجوع إلى الصفحة السابقة</a>
</div>
</body>
</html>
