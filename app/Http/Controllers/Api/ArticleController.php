<?php

namespace App\Http\Controllers\Api;

use App\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ArticleRequest;
use App\Http\Resources\Api\ArticleResource;
use App\Models\Article;
use App\Repository\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    use ApiResponser;
    public function create(ArticleRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['user_id'] = $user->id;

        ArticleRepository::save($data);
        return $this->success(
            message: 'Successfully Added new Article'
        );
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['user_id'] = $user->id;

        ArticleRepository::save($data, $article);
        return $this->success(
            message:"Successfully update this category"
        );
    }

    public function show(Article $article)
    {
        return $this->success(
            ArticleResource::make($article),
            'Successfully got the detail of this article'
        );
    }

    public function listAll()
    {
        $articles = Article::paginate(5);
        return $this->withPagination($articles, ArticleResource::class);
    }

    public function delete(Article $article)
    {
        Storage::disk('public')->delete($article->image);
        $article->delete();
        return $this->success(
            message: 'Succesfully delete this article'
        );
    }
}
