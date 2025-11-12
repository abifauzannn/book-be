<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['author', 'publisher']); // include relasi

        // Filter by title
        if ($request->has('title')) {
            $query->where('title', 'like', "%{$request->title}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['id', 'title', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Ambil semua data jika all=true
        if ($request->boolean('all')) {
            $books = $query->get();
        } else {
            $perPage = (int) $request->get('per_page', 5);
            $books = $query->paginate($perPage);
        }

        return response()->json([
            'success' => true,
            'message' => 'Books retrieved successfully',
            'data' => $books
        ]);
    }

    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('books')->where(fn($query) => $query->where('publisher_id', $request->publisher_id))
            ],
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $book = Book::create($request->only('title','author_id','publisher_id','description'));

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book->load(['author','publisher'])
        ], 201);
    }

    public function show(Book $book)
    {
        return response()->json([
            'success' => true,
            'message' => 'Book retrieved successfully',
            'data' => $book->load(['author','publisher'])
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'title' => [
            'sometimes',
            'string',
            'max:255',
            Rule::unique('books')
                ->where(fn($query) => $query->where('publisher_id', $request->publisher_id))
                ->ignore($book->id)
        ],
            'author_id' => 'sometimes|exists:authors,id',
            'publisher_id' => 'sometimes|exists:publishers,id',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $book->update($request->only('title','author_id','publisher_id','description'));

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => $book->load(['author','publisher'])
        ]);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully'
        ]);
    }
}

