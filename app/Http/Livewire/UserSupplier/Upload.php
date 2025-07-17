<?php

namespace App\Http\Livewire\UserSupplier;

use Livewire\Component;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use Livewire\WithFileUploads;

class Upload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.product.upload');
    }

    public function save()
    {
        ini_set('memory_limit', '-1');
        $this->validate([
            'file'=>'required|mimes:xls,xlsx|max:51200' // 50MB maksimal
        ]);
        
        $path = $this->file->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $data = $reader->load($path);
        $sheetData = $data->getActiveSheet()->toArray();

        if(count($sheetData) > 0){
            $countLimit = 1;
            foreach($sheetData as $key => $i){
                if($key<=2) continue; // skip header
                
                $nama = $i[0];
                $produk = $i[1];
                $harga = $i[2];
                
                $product = Product::where('keterangan',$produk)->first();
                if(!$product){
                    $product = new Product();
                    $product->is_migrate = 1;
                }
                
                $product->type ='Konsinyasi';
                $product->keterangan = $produk;
                $product->harga_jual = $harga;
                $product->product_uom_id = 1; // PCS
                $product->qty = 0;
                $product->save();

                $supplier = Supplier::where('nama_supplier',$nama)->first();
                if(!$supplier){
                    $supplier = new Supplier();
                    $supplier->nama_supplier = $nama;
                    $supplier->save();
                }

                $supplier_product = SupplierProduct::where(['product_id'=>$product->id,'id_supplier'=>$supplier->id])->first();
                if(!$supplier_product){
                    $supplier_product = new SupplierProduct();
                    $supplier_product->product_id = $product->id;
                    $supplier_product->id_supplier = $supplier->id;
                    $supplier_product->save();
                }
                $supplier_product->price = $harga;
                $supplier_product->save();
            }
        }

        session()->flash('message-success',__('Data berhasil di upload'));

        return redirect()->route('user-supplier.index');
    }
}