<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\categories;
    use App\Models\categories_prices;
    use App\Models\CategoryDiscount;
    use App\Models\PackageDiscount;
    use App\Models\Packages;
    use App\Models\packages_prices;
    use Illuminate\Http\Request;
    use Brian2694\Toastr\Facades\Toastr;

    class DiscountController extends Controller
    {
        public function indexPackages()
        {
            $discounts = PackageDiscount::all();

            return view('Dashboard.discounts.packages', compact('discounts'));
        }

        public function indexCategories()
        {
            $discounts = CategoryDiscount::all();

            return view('Dashboard.discounts.categories', compact('discounts'));
        }

        public function createDiscount()
        {
            $packages = Packages::all();
            $categories = categories::all();
            return view('Dashboard.discounts.create_discount', compact('packages', 'categories'));
        }

        public function CategoryPrice($id)
        {
            $price = categories_prices::where('category_id', $id)->firstOrFail();
            return response()->json([
                'at_home' => $price->at_home,
                'at_spa' => $price->at_spa,
            ]);
        }

        public function PackagePrice($id)
        {
            $price = packages_prices::where('package_id', $id)->firstOrFail();
            return response()->json([
                'at_home' => $price->at_home,
                'at_spa' => $price->at_spa,
            ]);
        }

        public function storeAllDiscount(Request $request)
        {
            try {
                // 🎯 Let's validate the incoming data before we throw a party! 🎉
                $validatedData = $request->validate([
                    'booking_type' => 'required|string|in:category,package', // Make sure they choose a type! No indecisiveness allowed! 🤔
                    'categories' => 'required_if:booking_type,category|integer|exists:categories,id', // Categories gotta be real! No imaginary friends! 🥳
                    'packages' => 'required_if:booking_type,package|integer|exists:packages,id', // Packages must exist! We don’t deal with ghosts! 👻
                    'at_home' => 'required|numeric', // Home price? Show me the money! 💰
                    'at_spa' => 'required|numeric', // Spa price? Relaxation doesn't come cheap! 💆‍♂️💵
                    'discount_at_home' => 'required|numeric|min:0', // Discounts better not be negative or we’re gonna need a math tutor! 📉
                    'discount_at_spa' => 'required|numeric|min:0', // Same goes for spa discounts! No ninja tricks here! 🥷
                ]);

                // 🎩 Time to put on our discount wizard hats! 🧙‍♂️
                if ($validatedData['booking_type'] === 'category') {
                    // Saving discount for categories! It's a category party! 🎊
                    $discount = new CategoryDiscount();
                    $discount->category_id = $validatedData['categories'];
                    $discount->at_home = $validatedData['discount_at_home']; // 🎈 Price after discount - like magic! 🎩✨
                    $discount->at_spa = $validatedData['discount_at_spa']; // 🎈 Spa prices getting slashed! 💥
                    $discount->is_active = true; // 🎉 It's alive! 🎉
                    $discount->created_by = auth('admin')->id(); // Who's the genius behind this? 😎
                    $discount->save(); // Save it like it’s hot! 🔥
                } elseif ($validatedData['booking_type'] === 'package') {
                    // Saving discount for packages! 🎁 Bring on the goodies! 🥳
                    $discount = new PackageDiscount();
                    $discount->package_id = $validatedData['packages'];
                    $discount->at_home = $validatedData['discount_at_home']; // 🎈 Another price magic trick! 🎩✨
                    $discount->at_spa = $validatedData['discount_at_spa']; // 🎈 Spa prices doing the cha-cha! 💃
                    $discount->is_active = true; // This discount is ready to rock! 🤘
                    $discount->created_by = auth('admin')->id(); // We’ve got a rockstar in the house! 🎸
                    $discount->save(); // Save it like it’s hot again! 🔥
                }

                // 🥳 Celebration time! We've successfully added a discount! 🎊
                Toastr::success('😀 تم إضافة الخصم بنجاح! تصفيق للجميع!', 'نجاح 🎉');

                // Redirecting to the right party! 🎈
                return redirect()->route($validatedData['booking_type'] === 'category' ? 'admin.getCategoryDiscount' : 'admin.getPackageDiscount');

            } catch (\Illuminate\Validation\ValidationException $e) {
                // Oops! Someone’s made a boo-boo! Let's catch those validation errors! 🚫
                return redirect()->back()->withErrors($e->validator->errors())->withInput();

            } catch (\Exception $e) {
                // Uh-oh! Something went wrong! 🥴 Let's not panic! Keep calm and debug on! 🐞
                return redirect()->back()->with('error', 'عذرًا! حدث خطأ ما أثناء إضافة الخصم. 🤷‍♂️');
            }
        }

    }
