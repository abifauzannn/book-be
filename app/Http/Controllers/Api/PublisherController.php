<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::query();

        // Filter by name
        if ($request->has('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id'); // default sort by id
        $sortOrder = $request->get('sort_order', 'desc'); // default descending

        // Hanya allow beberapa kolom untuk sorting
        $allowedSorts = ['id', 'name', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        if (!in_array(strtolower($sortOrder), ['asc','desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = (int) $request->get('per_page', 5);
        $publishers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Publishers retrieved successfully',
            'data' => $publishers
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $publisher = Publisher::create($request->only('name','address'));

        return response()->json([
            'success' => true,
            'message' => 'Publisher created successfully',
            'data' => $publisher
        ], 201);
    }

    public function show(Publisher $publisher)
    {
        return response()->json([
            'success' => true,
            'message' => 'Publisher retrieved successfully',
            'data' => $publisher
        ]);
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $publisher->update($request->only('name','address'));

        return response()->json([
            'success' => true,
            'message' => 'Publisher updated successfully',
            'data' => $publisher
        ]);
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Publisher deleted successfully'
        ]);
    }

    public function all()
{
    $publishers = Publisher::all(); // ambil semua author tanpa paginate
    return response()->json([
        'success' => true,
        'message' => 'All authors retrieved successfully',
        'data' => $publishers
    ]);
}


}
