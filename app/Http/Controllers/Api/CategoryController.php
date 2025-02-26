<?php

namespace App\Http\Controllers\Api;

use App\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use ApiResponser;

    public function create(CategoryRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        Category::create([
            'user_id' => $user->id,
            'name' => $data['name']
        ]);

        return $this->success(
            message: 'Successfully Added new Category'
        );
    }

    public function show(Category $category)
    {
        return $this->success(
            CategoryResource::make($category),
            'Successfully got the detail of this category'
        );
    }

    public function listAll()
    {
        $categories = Category::paginate(5);
        return $this->withPagination($categories, CategoryResource::class);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update([
            'name' => $data['name']
        ]);

        return $this->success(
            message:"Successfully update this category"
        );
    }

    public function delete(Category $category)
    {
        $category->delete();
        return $this->success(
            message: 'Succesfully delete this category'
        );
    }
}
