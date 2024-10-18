<?php

    namespace App\Http\Controllers;

    use App\Models\categories;
    use App\Models\Services;
    use Illuminate\Http\Request;

    class ShowCategoryController extends Controller
    {
        public function index($type = null, $namePart)
        {
            $category = categories::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(str_replace('_', ' ', $namePart)) . '%'])->firstOrFail();

            $service = Services::where('id', $category->service_id)->firstOrFail();
            //dd($category);
            // $service = Services::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(str_replace('_', ' ', $namePart)) . '%'])->firstOrFail();

            //$serviceId = $service->id;

//            $categories = categories::with(['service', 'prices', 'employees', 'availability'])
//                ->whereHas('service', function ($query) use ($serviceId) {
//                    $query->where('id', $serviceId);
//                })
//                ->get();
            return view('front.categories.show', compact('category', 'service', 'type'));
        }
    }
