<?php

namespace App\Services;

class NewsService
{ 
    /**
     * Fetch all news items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllNews()
    {
        return \App\Models\News::all();
    }

    /**
     * Create a new news item.
     *
     * @param array $data
     * @return \App\Models\News
     */
    public function createNews(array $data)
    {
        return \App\Models\News::create($data);
    }

    /**
     * Find a news item by ID.
     *
     * @param int $id
     * @return \App\Models\News|null
     */
    public function findNewsById(int $id)
    {
        return \App\Models\News::find($id);
    }

    /**
     * Update a news item.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\News|null
     */
    public function updateNews(int $id, array $data)
    {
        $news = $this->findNewsById($id);
        if ($news) {
            $news->update($data);
            return $news;
        }
        return null;
    }

    /**
     * Delete a news item.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteNews(int $id)
    {
        $news = $this->findNewsById($id);
        if ($news) {
            return $news->delete();
        }
        return null;
    }

    /**
     * Handle the upload of a news item, including file and JSON validation.
     *
     * @param array $requestData
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function handleNewsUpload(array $requestData, $file)
    {
        // Decode JSON from the 'data' field
        $data = json_decode($requestData['data'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => 'Invalid JSON in data field',
                'status' => 422
            ];
        }

        // Validate the decoded JSON
        $validator = \Validator::make($data, [
            'text' => 'required|string',
            // add other fields as needed
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors(),
                'status' => 422
            ];
        }

        // File and JSON are valid here
        $path = $file->store('uploads', 'public');

        // Optionally, create the news item in the database
        $news = $this->createNews([
            'text' => $data['text'],
            'image_webp' => $path,
        ]);

        return [
            'news' => $news,
            'file_path' => $path,
            'status' => 201
        ];
    }
}