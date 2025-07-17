<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$filter=[],$is_confirm_delete=false,$selected_id=0;
    protected $listeners = ['refresh'=>'$refresh'];
    public function render()
    {
       $data = $this->getData();

        return view('livewire.product.index')->with(['data'=>$data->paginate(200)]);
    }

    public function getData()
    {
        $data = Product::with(['uom'])->orderBy('id','DESC');

        foreach($this->filter as $field => $value){
            if($value=="") continue;
            if($field =='keterangan'){
                $data->where(function($table) use($value){
                    $table->where('keterangan','LIKE',"%{$value}%")
                    ->orWhere('kode_produksi','LIKE',"%{$value}%");
                });
            }elseif($field=='minimum_stock'){
                if($value==1) $data->whereNotNull('minimum_stok')->whereRaw('qty < minimum_stok');
                if($value==2) $data->whereNotNull('minimum_stok')->whereRaw('qty = minimum_stok');
            }else{
                $data->where($field,$value);
            }
        }

        return $data;
    }

    public function mount()
    {
        \LogActivity::add('Product');
    }

    public function set_delete($id)
    {
        $this->is_confirm_delete = true; $this->selected_id = $id;
    }

    public function cancel_delete()
    {
        $this->is_confirm_delete = false; $this->selected_id = 0;
    }

    public function delete()
    {
        Product::find($this->selected_id)->delete();
        
        $this->selected_id = 0;
        $this->emit('message-success','Data berhasil dihapus');
        $this->emit('refresh');
    }

    public function download()
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Product")
                                    ->setDescription("Product")
                                    ->setKeywords("office 2007 openxml php")
                                    // ->setCategory("Member")
                                    ;

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('689a3b');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'DATA PRODUCT');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(false);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Status')
                    ->setCellValue('C3', 'Type')
                    ->setCellValue('D3', 'Kode Produk / Barcode')
                    ->setCellValue('E3', 'Produk')
                    ->setCellValue('F3', 'UOM')
                    ->setCellValue('G3', 'Stock')
                    ->setCellValue('H3', 'Moving Stock')
                    ->setCellValue('I3', 'Minimum Stock')
                    ->setCellValue('J3', 'Harga Jual Dasar')
                    ->setCellValue('K3', 'PPN')
                    ->setCellValue('L3', 'Harga Produksi')
                    ->setCellValue('M3', 'Harga Jual')
                    ->setCellValue('N3', 'Diskon')
                    ->setCellValue('O3', 'Harga Jual + Diskon');
                    
        $objPHPExcel->getActiveSheet()->getStyle('A3:O3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('c2d7f3');
        $objPHPExcel->getActiveSheet()->getStyle('A3:O3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:O3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        //$objPHPExcel->getActiveSheet()->freezePane('A4');
        $objPHPExcel->getActiveSheet()->setAutoFilter('B3:O3');
        $num=4;

        $data = $this->getData();

        foreach($data->get() as $k => $i){
        
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num,($k+1))
                ->setCellValue('B'.$num,$i->status==1?'Aktif':'Tidak Aktif')
                ->setCellValue('C'.$num,$i->type)
                ->setCellValue('D'.$num,$i->kode_produksi)
                ->setCellValue('E'.$num,$i->keterangan)
                ->setCellValue('F'.$num,(isset($i->uom->name) ? $i->uom->name : ''))
                ->setCellValue('G'.$num,$i->qty)
                ->setCellValue('H'.$num,$i->qty_moving)
                ->setCellValue('I'.$num,$i->minimum_stok)
                ->setCellValue('J'.$num,$i->harga)
                ->setCellValue('K'.$num,$i->ppn)
                ->setCellValue('L'.$num,$i->harga_produksi)
                ->setCellValue('M'.$num,$i->harga_jual)
                ->setCellValue('N'.$num,$i->diskon)
                ->setCellValue('O'.$num,$i->harga_jual - $i->diskon);

            $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getNumberFormat()->setFormatCode('@');

            $num++;
        }
        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Iuran-'. date('d-M-Y'));
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="produk-' .date('d-M-Y') .'.xlsx"');
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
        },'produk-' .date('d-M-Y') .'.xlsx');
    }
}
