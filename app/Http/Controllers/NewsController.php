<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;

class NewsController extends Controller
{
    public function __construct(private NewsService $newsService)
    {}
    
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
