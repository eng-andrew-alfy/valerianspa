<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\Place;
    use Brian2694\Toastr\Facades\Toastr;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Session;

    class PlaceController extends Controller
    {
        public function index()
        {
            $places = Place::with('admin')->orderBy('created_at', 'DESC')->get();
            return view('Dashboard.places.index', compact('places'));
        }

        public function create()
        {
            return view('Dashboard.places.create');
        }

        public function store(Request $request)
        {
            // Validate and store the data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'coordinates' => 'required|json',
            ]);

            $place = new Place();
            $place->name = $validated['name'];
            $place->coordinates = $validated['coordinates'];
            $place->created_by = auth('admin')->id();
            $place->save();

            Toastr::success(' 😀  لقد تم أضافة المكان بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.places.index');

        }

        public function show()
        {
        }

        public function edit($id)
        {
            // Find the place by ID
            $place = Place::findOrFail($id);

            // Return the edit view with the place data
            return view('Dashboard.places.edit', compact('place'));
        }

        public function update(Request $request, $id)
        {
            // Validate and update the data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'coordinates' => 'required|json',
            ]);

            // Find the place to be updated
            $place = Place::findOrFail($id);
            $place->name = $validated['name'];
            $place->coordinates = $validated['coordinates'];
            $place->save();

            Toastr::success(' 😀  لقد تم تحديث المكان بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.places.index');
        }

        public function destroy(Place $place)
        {
            $place->delete();

            return response()->json(['success' => true]);
        }

        public function updateStatus(Request $request, $id)
        {
            $place = Place::findOrFail($id);
            $place->is_active = $request->is_active;
            if ($place->save()) {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }
    }
