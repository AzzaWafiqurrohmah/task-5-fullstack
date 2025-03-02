<?php

namespace App\Http\Controllers\Web;

use App\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CategoryRequest;
use App\Http\Resources\Web\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('pages.category.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => $data['name']
        ]);

        return $this->success(
            CategoryResource::make($category),
            'Successfully added new category'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->success(
            CategoryResource::make($category),
            "Successfuly got this category"
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return $this->success(
            message: "Successfully update this category"
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->success(
            message: "Successfully delete this category"
        );
    }

    public function datatables()
    {
        return datatables(Category::query())
            ->addIndexColumn()
            ->addColumn('action', fn ($category) => view('pages.category.action', compact('category')))
            ->toJson();
    }
}
