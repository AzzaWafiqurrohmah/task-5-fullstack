<?php

namespace App\Repository;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;

class ArticleRepository{
    public static function save(array $data, ?Article $article = null)
    {
        if (isset($data['image']))
            $data['image'] = $data['image']->storePublicly('article', 'public');

        if (($article) && isset($data['image']))
            Storage::disk('public')->delete($article->image);

        if ($article) {
            $article->update($data);
            return $article;
        }

        return Article::create($data);
    }
}