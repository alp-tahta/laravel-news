<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function __construct(private NewsService $newsService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all news items using the NewsService
        $news = $this->newsService->getAllNews();

        return response()->json([
            'status' => 'success',
            'data' => \App\Http\Resources\NewsResource::collection($news),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the presence and type of fields
        try {
            $validated = $request->validate([
                'data' => 'required|string',
                'file' => 'required|file|mimes:webp|max:2048'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        }

        $result = $this->newsService->handleNewsUpload($request->only('data'), $request->file('file'));

        if (isset($result['error'])) {
            return response()->json([
                'status' => 'error',
                'errors' => $result['error'],
            ], $result['status']);
        }

        return response()->json([
            'status' => 'success',
            'data' => new \App\Http\Resources\NewsResource($result['news']),
            'file_path' => $result['file_path']
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = $this->newsService->findNewsById((int) $id);

        if (!$news) {
            return response()->json([
                'status' => 'error',
                'message' => 'Haber bulunamadı.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new \App\Http\Resources\NewsResource($news),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = $this->newsService->findNewsById((int) $id);
        if (!$news) {
            return response()->json([
                'status' => 'error',
                'message' => 'Haber bulunamadı.',
            ], 404);
        }
        $this->newsService->deleteNews((int) $id);
    }
}
