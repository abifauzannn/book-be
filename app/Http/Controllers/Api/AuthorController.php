<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    // List authors with optional filter by name, sorting, pagination
    public function index(Request $request)
    {
        $query = Author::query();

        // Filter by name
        if ($request->has('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id'); // default sort by id
        $sortOrder = $request->get('sort_order', 'desc'); // default descending

        $allowedSorts = ['id', 'name', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = (int) $request->get('per_page', 5);
        $authors = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Authors retrieved successfully',
            'data' => $authors
        ]);
    }

    // Create a new author
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $author = Author::create($request->only('name','bio'));

        return response()->json([
            'success' => true,
            'message' => 'Author created successfully',
            'data' => $author
        ], 201);
    }

    // Show single author
    public function show(Author $author)
    {
        return response()->json([
            'success' => true,
            'message' => 'Author retrieved successfully',
            'data' => $author
        ]);
    }

    // Update author
    public function update(Request $request, Author $author)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $author->update($request->only('name','bio'));

        return response()->json([
            'success' => true,
            'message' => 'Author updated successfully',
            'data' => $author
        ]);
    }

    // Delete author
    public function destroy(Author $author)
    {
        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author deleted successfully'
        ]);
    }

    public function all()
{
    $authors = Author::all(); // ambil semua author tanpa paginate
    return response()->json([
        'success' => true,
        'message' => 'All authors retrieved successfully',
        'data' => $authors
    ]);
}
}
