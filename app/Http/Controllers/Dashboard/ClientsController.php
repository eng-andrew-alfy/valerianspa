<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Brian2694\Toastr\Facades\Toastr;
    use Illuminate\Http\Request;

    class ClientsController extends Controller
    {
        public function index()
        {
            $users = User::all();
            return view('Dashboard.clients.index', compact('users'));
        }

        public function update(Request $request)
        {
            $user = User::find($request->ID);
            $user->name = $request->Name_Customer;
            $user->phone = $request->Phone_Customer;
            $user->save();
            Toastr::success(' 😀  لقد تم تحديث معلومات العميل بنجاح', 'عملية ناجحة');

            return redirect()->back();
        }
    }
