<?php

namespace App\Http\Controllers\Web;

use App\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Repository\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::all();
        return view('pages.article.index', [
            'articles' => $articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('pages.article.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    { 
        $data = $request->validated();
        $user = Auth::user();
        $data['user_id'] = $user->id;

        ArticleRepository::save($data);
        return to_route('articles.index')->with('alert_s', 'Successfully added new Article');

    }


    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Pesan error login gagal
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('pages.article.edit', [
            'categories' => $categories,
            'article' => $article
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $data = $request->validated();
        $user = Auth::user();
        $data['user_id'] = $user->id;

        ArticleRepository::save($data, $article);
        return to_route('articles.index')->with('alert_s', 'Successfully update this Article');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Storage::disk('public')->delete($article->image);
        $article->delete();
        return $this->success(Article::all(), message: 'Successfully delete this Article');
    }

    public function search(Request $request)
    {
        $article = Article::query()
            ->where('title', 'like', '%'.$request->search_string.'%')
            ->orWhere('content', 'like', '%'.$request->search_string.'%')
            ->get();

        if($article->count() <= 0)
            return response()->json([
                'status' => 'nothing'
            ])->setStatusCode(200);

        return response()->json([
            'articles' => $article
        ]);
    }

    public function get()
    {
        $article = Article::all();
        $length = $article->count();
        return response()->json([
            'articles' => $article,
            'length' => $length
        ]);
    }
}
