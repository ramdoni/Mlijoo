<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    /**
     * Index
     * @return json
     */
    public function index(Request $request)
    {
        $products = Product::selectRaw("id, kode_produk, keterangan, harga, harga_jual, image");

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $products->where(function ($q) use ($search) {
                $q->where('keterangan', 'LIKE', "%$search%")
                ->orWhere('kode_produk', 'LIKE', "%$search%");
            });
        }

        $products = $products->latest()->paginate(20);

        // Ubah image menjadi URL penuh
        $productsData = $products->getCollection()->transform(function ($item) {
            $item->image = $item->image 
                ? asset($item->image) 
                : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $productsData,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|string|max:100|unique:products,kode_produk',
            'keterangan'  => 'required|string',
            'harga'       => 'required|numeric',
            // 'harga_jual'  => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
        ]);

        $product = Product::create([
            'kode_produk' => $validated['kode_produk'],
            'keterangan'  => $validated['keterangan'],
            'harga'       => $validated['harga'],
            // 'harga_jual'  => $validated['harga_juSim Caral'],
            // 'image'       => $imageName,
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storePubliclyAs("product/{$product->id}",$imageName,'public');
            $product->update([
                'image' => "storage/product/{$product->id}/{$imageName}" 
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil disimpan',
            'data'    => $product,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id'          => 'required|exists:products,id',
            'kode_produk' => 'required|string|max:100|unique:products,kode_produk,' . $request->id,
            'keterangan'  => 'required|string',
            'harga'       => 'required|numeric',
            // 'harga_jual'  => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
        ]);

        $product = Product::find($request->id);

        if ($request->hasFile('image')) {
            // Hapus image lama jika ada
            if ($product->image && Storage::exists($product->image)) {
                Storage::delete($product->image);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->storePubliclyAs("product/{$product->id}",$imageName,'public');
            $product->update([
                'image' => "storage/product/{$product->id}/{$imageName}" 
            ]);
        }

        $product->update([
            'kode_produk' => $validated['kode_produk'],
            'keterangan'  => $validated['keterangan'],
            'harga'       => $validated['harga'],
            // 'harga_jual'  => $validated['harga_jual'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data'    => $product,
        ]);
    }

    public function data(Request $r)
    {   
        $validator = \Validator::make($r->all(), [
            'token'=>'required',
        ]);
        
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';

        $data = Product::orderBy('keterangan','ASC');

        if($keyword) $data->where(function($table) use($keyword){
                            $table->where('kode_produksi','LIKE',"%{$keyword}%")
                            ->orWhere('keterangan','LIKE',"%{$keyword}%");
                        });
        $items = [];
        
        if(isset($r->all_data) and $r->all_data==1)
            $data = $data->get();
        else
            $data = $data->paginate(10);

        foreach($data as $k => $item){
            $items[$k]['id'] = $item->id;
            $items[$k]['keterangan'] = $item->keterangan;
            $items[$k]['text'] = $item->kode_produksi .' / '. $item->keterangan . " - Rp. ".format_idr($item->harga_jual);
            $items[$k]['harga'] = format_idr($item->harga_jual);
            $items[$k]['harga_number'] = $item->harga_jual;
            $items[$k]['kode_produk'] = $item->kode_produksi;
            $items[$k]['qty'] = $item->qty;
        }

        return response()->json(['message'=>'success','items'=>$items,'total_count'=>count($items)], 200);
    }
}