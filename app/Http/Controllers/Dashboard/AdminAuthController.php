<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\Admin;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\RateLimiter;

    class AdminAuthController extends Controller
    {

        // عرض الصفحة الرئيسية
        public function index()
        {
            return view('Dashboard.home.index');
        }

        // عرض نموذج تسجيل الدخول
        public function showLoginForm()
        {
            return view('Dashboard.auth.login');
        }

        // معالجة تسجيل الدخول

        public function login(Request $request)
        {
            // تحقق من صحة المدخلات
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // فلترة المدخلات
            $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($request->password, FILTER_SANITIZE_STRING);

            // البحث عن المشرف بناءً على البريد الإلكتروني
            $admin = Admin::where('email', $email)->first();

            // التحقق إذا كان الحساب مقفلًا بشكل دائم
            if ($admin && $admin->status == 'blocked') {
                return redirect()->back()->withErrors(['email' => 'هناك خطأ في البريد الإلكتروني أو كلمة السر'])->withInput();
            }

            // التحقق إذا كان الحساب مقفلًا مؤقتًا
            if ($admin && $admin->locked_until && now()->lessThan($admin->locked_until)) {
                // إذا حاول الدخول أثناء قفل الحساب مؤقتًا يتم قفل الحساب بشكل دائم
                $this->lockAccountPermanently($admin);
                return redirect()->back()->withErrors(['email' => 'هناك خطأ في البريد الإلكتروني أو كلمة السر'])->withInput();
            }

            // محاولة تسجيل الدخول
            $credentials = ['email' => $email, 'password' => $password];
            $remember = $request->has('remember');

            if (Auth::guard('admin')->attempt($credentials, $remember)) {
                // مسح محاولات تسجيل الدخول بعد تسجيل الدخول بنجاح
                RateLimiter::clear($request->ip());

                return redirect()->intended(route('admin.dashboard'));
            }

            // زيادة عدد المحاولات الفاشلة
            RateLimiter::hit($request->ip(), 60); // محاولات تسجيل الدخول لكل 60 دقيقة

            // قفل الحساب بعد 3 محاولات فاشلة
            if (RateLimiter::tooManyAttempts($request->ip(), 3)) {
                $this->lockAccountTemporarily($admin);
                return redirect()->back()->withErrors(['email' => 'هناك خطأ في البريد الإلكتروني أو كلمة السر'])->withInput();
            }

            // إعادة توجيه في حال فشل تسجيل الدخول
            return redirect()->back()->withErrors(['email' => 'هناك خطأ في البريد الإلكتروني أو كلمة السر'])->withInput();
        }

        // قفل الحساب لمدة 3 ساعات
        protected function lockAccountTemporarily($admin)
        {
            $admin->locked_until = now()->addHours(3);
            $admin->save();
        }

        // قفل الحساب بشكل دائم
        protected function lockAccountPermanently($admin)
        {
            $admin->status = 'blocked';
            $admin->save();
        }

        // معالجة تسجيل الخروج
        public function logout()
        {
            Auth::guard('admin')->logout();
            return redirect('/admin/login');
        }
    }
