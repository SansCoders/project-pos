<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllCategory()
    {
        $categories = CategoryProduct::paginate(10);
        return view('admin.category-product', compact('categories'));
    }
    public function storeCategory(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib diisi',
            'min' => ':attribute harus diisi minimal :min huruf',
            'max' => ':attribute harus diisi maksimal :max huruf',
            'unique' => ':attribute tersebut sudah ada',
            'name.unique' => 'nama tersebut sudah ada',
        ];
        $request->validate([
            'name' => 'required|min:3|max:90|unique:category_products',
        ], $messages);

        $category = new CategoryProduct([
            'name' => $request->name,
        ]);
        $category->save();
        return redirect()->back()->with('success', 'Kategori Berhasil Ditambah');
    }

    public function showCategory($id)
    {
        $categories = CategoryProduct::paginate(10);
        return view('admin.category-product', compact('categories'));
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3|max:90|unique:category_products',
        ]);

        $eCP = CategoryProduct::find($id);
        $eCP->name = htmlspecialchars($request->name, ENT_QUOTES);
        $eCP->save();
        return redirect()->back()->with('success', 'editted');
    }
}
