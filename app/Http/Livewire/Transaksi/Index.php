<?php

namespace App\Http\Livewire\Transaksi;

use Livewire\Component;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Product;
use App\Models\InvoiceTransaksi;
use App\Models\UserMember;
use App\Models\InvoiceTransaksiItem;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filter=[],$filter_created_start,$filter_created_end;
    public $penjualan_hari_ini=0,$transaksi_hari_ini=0,$penjualan_bulan_ini=0,$transaksi_bulan_ini=0;
    public $selected_item,$alasan,$cetak_invoice=false,$check_id=[],$check_all=0,$no_invoice,$total_invoice=0;
    public $due_date,$user_members=[];
    protected $listeners = ['void'=>'void','calc_invoice'=>'calc_invoice','refresh'=>'reload'];
    public function render()
    {
        $data = $this->get_data();
        $total = clone $data;

        return view('livewire.transaksi.index')->with(['data'=>$data->paginate(500),'total'=>$total->sum('amount')]);
    }

    public function reload()
    {
        $this->render();$this->mount();
    }

    public function mount()
    {
        $this->penjualan_hari_ini = Transaksi::where('status',1)->whereDate('created_at',date('Y-m-d'))->sum('amount');
        $this->transaksi_hari_ini = Transaksi::where('status',1)->whereDate('created_at',date('Y-m-d'))->count();
        $this->penjualan_bulan_ini = Transaksi::where('status',1)->whereMonth('created_at',date('m'))->whereYear('created_at',date('Y'))->sum('amount');
        $this->transaksi_bulan_ini = Transaksi::where('status',1)->whereMonth('created_at',date('m'))->whereYear('created_at',date('Y'))->count();
        $this->user_members = UserMember::orderBy('name','ASC')->get();
        
        \LogActivity::add('Transaksi');
    }
 
    public function clearFilter()
    {
        $this->filter = [];$this->reset('filter_created_start','filter_created_end');
    }

    public function calc_invoice()
    {
        $this->no_invoice = 'INV/'.date('ymd').'/'.str_pad((InvoiceTransaksi::count()+1),4, '0', STR_PAD_LEFT);
        $this->total_invoice = Transaksi::whereIn('id',$this->check_id)->sum('amount');
    }

    public function get_data()
    {
        $data = Transaksi::with('anggota')->where('is_temp',0)->orderBy('id','DESC');

        if($this->filter){
            foreach($this->filter as $field =>$value){
                if($value=="") continue;
                if($field=='no_transaksi'){
                    $data->where($field, "LIKE", "%{$value}%");
                }elseif($field=='pembayaran'){
                    if($value==1) 
                        $data->whereNotNull('payment_date');
                    else
                        $data->whereNull('payment_date');
                }elseif($field=='status'){
                    $data->where(function($table) use($value){
                        $table->where('status','')->orWhere('status',$value);
                    });
                }else{
                    $data->where($field,$value);
                }
            }
        }

        if($this->filter_created_start and $this->filter_created_end){
            if($this->filter_created_start == $this->filter_created_end)
                $data->whereDate('created_at',$this->filter_created_start);
            else{
                $data->whereDate('created_at', '>=', $this->filter_created_start)->whereDate('created_at', '<=', $this->filter_created_end);
                // $data->whereBetween('created_at',[$this->filter_created_start,$this->filter_created_end]);
            }
        }

        return $data;
    }

    public function submitInvoice()
    {
        $this->validate([
            'check_id'=>'required|array'
        ],[
            'check_id.required' => "Transaksi harus dipilih"
        ]);

        $invoice = new InvoiceTransaksi();
        $invoice->no_invoice = $this->no_invoice;
        if($this->due_date) $invoice->due_date = $this->due_date;
        $invoice->status = 0;
        $invoice->total_item = count(array_filter($this->check_id));
        $invoice->amount = $this->total_invoice;
        $invoice->save();

        foreach(Transaksi::whereIn('id',$this->check_id)->get() as $k => $item){
            $invoice_item = new InvoiceTransaksiItem();
            $invoice_item->invoice_transaksi_id = $invoice->id;
            $invoice_item->transaksi_id = $item->id;
            $invoice_item->save();

            $item->invoice_transaksi_id = $invoice->id;
        }

        session()->flash('message-success',__('Invoice berhasil dibuat'));

        return redirect()->route('invoice-transaksi.detail',$invoice->id);
    }

    public function check_all_()
    {
        if($this->check_all==1){
            foreach($this->get_data()->get() as $k => $item){
                if($item->status==1 and $item->payment_date=="") $this->check_id[$k] = $item->id;
            }
        }
    }

    public function updated($propertyName)
    {
        if($propertyName=='check_all' and $this->check_all==0) $this->check_id = [];
    }

    public function void($id)
    {
        $this->selected_item = Transaksi::find($id);
    }

    public function voidTransaksi()
    {
        $this->validate([
            'alasan' => 'required'
        ]);

        $this->selected_item->status = 4; //void
        $this->selected_item->void_alasan = $this->alasan;
        $this->selected_item->void_date = date('Y-m-d');
        $this->selected_item->save();

        foreach($this->selected_item->items as $item){
            Product::find($item->product_id)->update(['qty'=>$item->product->qty + $item->qty]);
        }

        if($this->selected_item->jenis_transaksi==1){
            $anggota = UserMember::find($this->selected_item->user_member_id);
            if($anggota){
                $anggota->plafond_digunakan = $anggota->plafond_digunakan - $this->selected_item->amount;
                $anggota->save();
            }
        }

        $this->emit('message-success',"Data transaksi #{$this->selected_item->no_transaksi} berhasil di void.");
        $this->emit('close-modal');
    }

    public function downloadExcel()
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Transaksi")
                                    ->setDescription("Transaksi")
                                    ->setKeywords("office 2007 openxml php")
                                    // ->setCategory("Member")
                                    ;

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('689a3b');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'DATA TRANSAKSI');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(false);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Status')
                    ->setCellValue('C3', 'Jenis Transaksi')
                    ->setCellValue('D3', 'No Anggota')
                    ->setCellValue('E3', 'Nama Anggota')
                    ->setCellValue('F3', 'No Transaksi')
                    ->setCellValue('G3', 'Metode Pembayaran')
                    ->setCellValue('H3', 'Tanggal Transaksi')
                    ->setCellValue('I3', 'Status Pembayaran')
                    ->setCellValue('J3', 'Tanggal Pembayaran')
                    ->setCellValue('K3', 'Nominal')
                    ->setCellValue('L3', 'PPN')
                    ->setCellValue('M3', 'Total');
                    
        $objPHPExcel->getActiveSheet()->getStyle('A3:AQ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('c2d7f3');
        $objPHPExcel->getActiveSheet()->getStyle('A3:AQ3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:AQ3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(34);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        //$objPHPExcel->getActiveSheet()->freezePane('A4');
        $objPHPExcel->getActiveSheet()->setAutoFilter('B3:M3');
        $num=4;

        $data = $this->get_data();

        foreach($data->get() as $k => $i){
        
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num,($k+1))
                ->setCellValue('B'.$num,strip_tags(status_transaksi($i->status)))
                ->setCellValue('C'.$num,$i->jenis_transaksi==1?'Anggota' : 'Non Anggota')
                ->setCellValue('D'.$num,isset($i->anggota->no_anggota_platinum) ? $i->anggota->no_anggota_platinum : '-')
                ->setCellValue('E'.$num,isset($i->anggota->name) ? $i->anggota->name : '-')
                ->setCellValue('F'.$num,$i->no_transaksi)
                ->setCellValue('G'.$num,$i->metode_pembayaran ? metode_pembayaran($i->metode_pembayaran) : 'TUNAI')
                ->setCellValue('H'.$num,date('d-M-Y H:i',strtotime($i->created_at)))
                ->setCellValue('I'.$num,($i->payment_date ? 'Lunas' :'Belum Lunas'))
                ->setCellValue('J'.$num,$i->payment_date ? date('d-M-Y',strtotime($i->payment_date)) : '-')
                ->setCellValue('K'.$num,$i->amount - ($i->amount * 0.11))
                ->setCellValue('L'.$num,$i->amount * 0.11)
                ->setCellValue('M'.$num,$i->amount);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $num++;
        }
        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Iuran-'. date('d-M-Y'));
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="transaksi-' .date('d-M-Y') .'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        //header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        return response()->streamDownload(function() use($writer){
            $writer->save('php://output');
        },'transaksi-' .date('d-M-Y') .'.xlsx');
    }
}
