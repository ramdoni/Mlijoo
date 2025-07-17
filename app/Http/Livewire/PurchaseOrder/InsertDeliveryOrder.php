<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;
use App\Models\ProdusenDeliveryOrder;

class InsertDeliveryOrder extends Component
{
    public $do_number,$do_penerima,$do_date,$data;
    protected $listeners = ['reload'=>'$refresh'];
    public function render()
    {
        return view('livewire.purchase-order.insert-delivery-order');
    }

    public function mount(PurchaseOrder $data)
    {
        $this->data = $data;
        $this->do_number = 'DO-'. Auth::user()->id .'-'. date('dmy').'-'. $this->data->id;
        $this->do_date = date('Y-m-d');

        LogActivity::add('Insert Delivery Order');
    }

    public function submit()
    {
        $this->validate([
            'do_number'=>'required',
            'do_penerima'=>'required',
            'do_date'=>'required',
        ]);

        $this->data->do_penerima = $this->do_penerima;
        $this->data->do_number = $this->do_number;
        $this->data->status = 2;
        $this->data->save();

        /**
         * Insert otomatis masuk ke qty produk
         */
        foreach($this->data->details as $item){
            if(isset($item->product->id)){
                Product::find($item->product_id)->update(['qty'=> $item->qty + $item->product->qty]);
            }
        }

        ProdusenDeliveryOrder::create([
            'produsen_id'=>$this->data->produsen_id,
            'purchase_order_id'=>$this->data->id,
            'no_delivery_order'=>$this->data->do_number,
            'user_created_id'=>Auth::user()->id,
            'penerima_nama'=>$this->data->do_penerima
        ]);

        LogActivity::add('Insert Delivery Order #'. $this->data->do_number);

        $this->emit('reload');$this->emit('message-success','Data berhasil disimpan.');
    }
}