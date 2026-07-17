<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminCategoryController extends Controller
{

    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:categories,name'
    ]);

    $category = Category::create([
        'name' => $request->name
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Category created successfully',
        'data' => $category
    ], 201);
}

}