<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\Gift;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;

    class GiftsController extends Controller
    {
        public function index()
        {
            $gifts = Gift::with(['sender', 'recipient', 'category', 'package', 'order'])->get();
            return view('dashboard.gifts.index', compact('gifts'));
        }

        public function updateExpiry(Request $request)
        {
            $request->validate([
                'gift_id' => 'required|exists:gifts,id',
            ]);

            $gift = Gift::find($request->gift_id);

            if (!$gift) {
                return response()->json(['success' => false, 'message' => 'الهدية غير موجودة.'], 404);
            }
            $currentDate = \Carbon\Carbon::now();
            $newExpiryDate = $currentDate->addHours(9);


            try {
                $gift->expiry_date = $newExpiryDate;
                $gift->save();

            } catch (\Exception $e) {
                Log::error('Error updating expiry date', [
                    'error' => $e->getMessage(),
                    'gift_id' => $request->gift_id
                ]);
                return response()->json(['success' => false, 'message' => 'فشل في تحديث تاريخ انتهاء الصلاحية.'], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث تاريخ انتهاء الصلاحية بنجاح.',
                'new_expiry_date' => $newExpiryDate->format('Y-m-d H:i:s')


            ]);
        }

        public function edit(Gift $gift)
        {
            $gift = $gift->load('sender', 'recipient', 'category', 'package', 'order');
            return view('Dashboard.gifts.edit_order_gift', compact('gift'));
        }


    }
