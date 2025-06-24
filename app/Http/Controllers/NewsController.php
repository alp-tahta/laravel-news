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
        // First, validate the presence and type of fields
        try {
            $validated = $request->validate([
                'data' => 'required|string',
                'file' => 'required|file|mimes:jpg,jpeg,png,pdf,webp|max:48' // Max 2MB
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        }

        // Decode JSON from the 'data' field
        $data = json_decode($request->input('data'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON in data field'], 422);
        }

        // Now validate the decoded JSON
        $validator = Validator::make($data, [
            'text' => 'required|string',
            // add other fields as needed
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // File and JSON are valid here
        $file = $request->file('file');
        $path = $file->store('uploads', 'public'); // Optional: save file

        return response()->json([
            'message' => 'Upload successful',
            'data' => $data,
            'file_path' => $path
        ]);

        /*
            try {
                $validated = $request->validate([
                    'text' => 'required|string|max:5',
                    'image_webp' => 'required|string',
                ]);
            } catch (ValidationException $e) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $e->errors(),
                ], 422);
            }

            $news = $this->newsService->createNews($validated);

            return response()->json([
                'status' => 'success',
                'data' => new \App\Http\Resources\NewsResource($news),
            ], 201); */
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
                'message' => 'News not found',
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
        //
    }
}
