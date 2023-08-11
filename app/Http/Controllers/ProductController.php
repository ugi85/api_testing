<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'status' => '200',
            'data' => $products,
        ]);
    }

    public function show($id)
    {
        // Menampilkan detail produk berdasarkan ID
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Data not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $product,
        ]);

    }


    public function store(Request $request)
{
    // Validasi permintaan masuk
    $this->validate($request, [
        'no_product' => 'required',
        'product_name' => 'required',
        'product_price' => 'required|numeric',
        'product_description' => 'required',
        'product_category' => 'required',
        'product_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan kebutuhan
    ]);

    // Membuat produk baru
    $product = Product::create($request->except('product_image'));

    if ($request->hasFile('product_image')) {
       $request->file('product_image')->move('gambar/', $request->file('product_image')->getClientOriginalName());
       $product->product_image = $request->file('product_image')->getClientOriginalName();

    }

    // if ($request->hasFile('product_image')) {
    //     $imagePath = $request->file('product_image')->store('images', 'public'); // Simpan gambar di direktori public/images
    //     $product->product_image = $imagePath;
    // }

    $product->save();

    //User created, return success response
    return response()->json([
        'status' => '200',
        'success' => true,
        'message' => 'Add Product successfully',
        'data' => $product
    ], Response::HTTP_OK);
}


    public function update(Request $request, $id)
    {
        // var_dump ($id);
        // $product = JWTAuth::parseToken()->authenticate();

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'no_product' => 'required',
            'product_name' => 'required',
            'product_price' => 'required|numeric',
            'product_description' => 'required',
            'product_category' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        // Update data user
        $product = Product::findOrFail($id);
        $product->no_product= $request->no_product;
        $product->product_name = $request->product_name;
        $product->product_price = $request->product_price;
        $product->product_description = $request->product_description;
        $product->product_category = $request->product_category;
        $product->save();

        // Mengembalikan respons sukses
        return response()->json([
            'status' => '200',
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }



    public function destroy($id)
    {
        // Cari user yang ingin dihapus
        $product = Product::find($id);

        // Jika user tidak ditemukan, kembalikan pesan error
        if (!$product) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Hapus user
        $product_name = $product->product_name;
        $product->delete();

        // Kembalikan respon sukses
        return response()->json([
                    'status' => '200',
                    'success' => true,
                    'message' => "Data $product_name deleted successfully"
                ], Response::HTTP_OK);
    }
}
