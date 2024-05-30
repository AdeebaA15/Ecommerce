<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\categories;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::whereHas('category', function ($query) {
            $query->where('id', 1); // Change the condition as needed
        })->get();
        $categories = categories::all();
        return view('welcome', ['products' => $products, 'categories' => $categories]);
    }

    public function showAll()
    {
        $products = Product::all();
        $categories = categories::all();
        return view('products', ['products' => $products, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|string',
            'warranty' => 'nullable|string|max:255',
            'category_id' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validatedData = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $validatedData['image'] = 'images/products/' . $imageName;
        }

        Product::create($validatedData);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    public function showWebsite()
    {
        $products = Product::all();
        $categories = categories::all();
        return view('welcome', ['products' => $products, 'categories' => $categories]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required',
            'warranty' => 'required',
            'category_id' => 'nullable|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::find($id);
        $product->fill($request->except('image'));

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete($product->image);
            }
            $imagePath = $request->file('image')->store('product_images');
            $product->image = $imagePath;
        }

        $product->save();

        return redirect()->route('products')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function showCategoryProducts()
    {
        $products = Product::whereHas('category', function ($query) {
            $query->where('id', 1); // Change the condition as needed
        })->take(4)->get();
        $categories = categories::all();
        return view('welcome', ['products' => $products, 'categories' => $categories]);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $categoryId = $request->input('category_id');

        $query = Product::query();

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($searchTerm)) {
            $query->where('title', 'LIKE', '%' . $searchTerm . '%');
        }

        $products = $query->get();
        $categories = categories::all();

        return view('search_results', compact('products', 'categories'));
    }

    public function showAllProducts($category_id)
    {
        $category = categories::findOrFail($category_id);
        $products = Product::where('category_id', $category_id)->get();
        return view('all_products', compact('category', 'products'));
    }

    public function browse()
    {
        $products = Product::all();
        $categories = categories::all();
        return view('browse-products', compact('products', 'categories'));
    }

    public function filter(Request $request)
    {
        $maxPrice = $request->input('max_price', 10000);
        $products = Product::where('price', '<=', $maxPrice)->get();
        $categories = categories::all();
        return view('browse-products', ['products' => $products, 'categories' => $categories]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $categories = categories::all();
        return view('productdetails', compact('product', 'categories'));
    }

    public function listProducts(Request $request)
    {
        $minPrice = $request->input('min_price', 0);
        $maxPrice = $request->input('max_price', 200000);
        $sort = $request->input('sort', 'price_asc');

        $query = Product::whereBetween('price', [$minPrice, $maxPrice]);

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
        }

        $products = $query->get();
        $categories = categories::all();

        return view('browse-products', compact('products', 'categories', 'minPrice', 'maxPrice', 'sort'));
    }
}