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
}