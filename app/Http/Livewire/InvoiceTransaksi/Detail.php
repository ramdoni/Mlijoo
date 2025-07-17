<?php

namespace App\Http\Livewire\InvoiceTransaksi;

use Livewire\Component;
use App\Models\InvoiceTransaksi;
use App\Models\Transaksi;
use App\Models\UserMember;
use Livewire\WithFileUploads;

class Detail extends Component
{
    use WithFileUploads;
    public $data,$payment_date,$file_bukti_pembayaran,$metode_pembayaran;
    public function render()
    {
        return view('livewire.invoice-transaksi.detail');
    }

    public function mount(InvoiceTransaksi $data)
    {
        $this->data = $data;
    }

    public function bayar()
    {
        $validate = [
            'payment_date' => 'required',
            'metode_pembayaran' => 'required'
        ];
        if($this->file_bukti_pembayaran) $validate['file_bukti_pembayaran'] = 'file|mimes:xlsx,csv,xls,doc,docx,pdf,jpg,jpeg,png|max:51200'; //] 50MB Max
        
        $this->validate($validate);

        $this->data->payment_date = $this->payment_date;
        $this->data->status = 1; // paid
        $this->data->metode_pembayaran = $this->metode_pembayaran;
        
        if($this->file_bukti_pembayaran!="") {
            $name = $this->data->id.".".$this->file_bukti_pembayaran->extension();
            $this->file_bukti_pembayaran->storePubliclyAs("public/invoice-transaksi/{$this->data->id}", $name);
            $this->data->file = "storage/invoice-transaksi/{$this->data->id}/{$name}";
        }

        $this->data->save();

        foreach($this->data->items as $item){
            $transaksi = $item->transaksi;

            Transaksi::find($transaksi->id)->update(['payment_date'=>$this->payment_date]);

            if(isset($transaksi->anggota->id)){
                $anggota =  UserMember::find($transaksi->user_member_id);
                $anggota->plafond_digunakan = $anggota->plafond_digunakan-$transaksi->amount;
                $anggota->save();
            }
        }

        session()->flash('message-success','Pembayaran berhasil di submit');

        return redirect()->route('invoice-transaksi.detail',$this->data->id);
    }

    public function downloadExcel()
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Invoice")
                                    ->setDescription("Invoice Transaksi")
                                    ->setKeywords("office 2007 openxml php");
        $status = '';
        if($this->data->status==0) $status = 'Belum lunas';
        if($this->data->status==1) $status = 'Lunas';
        if($this->data->status==2) $status = 'Batal';

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('689a3b');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Invoice Number ')
                                            ->setCellValue('C1', $this->data->no_invoice)

                                            ->setCellValue('B2', "Status")
                                            ->setCellValue('C2', $status)

                                            ->setCellValue('B3', "Due Date")
                                            ->setCellValue('C3', ($this->data->due_date ? date('d-M-Y',strtotime($this->data->due_date)) : '-'))

                                            ->setCellValue('B4', "Payment Date")
                                            ->setCellValue('C4', ($this->data->payment_date ? date('d-M-Y',strtotime($this->data->payment_date)) : '-'))

                                            ->setCellValue('B5', "Total Item")
                                            ->setCellValue('C5', $this->data->total_item)

                                            ->setCellValue('B5', "Total Nominal")
                                            ->setCellValue('C5', $this->data->amount)
                                            ;

        $objPHPExcel->getActiveSheet()->getStyle('C5')->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(false);
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6', 'No')
                    ->setCellValue('B6', 'Status')
                    ->setCellValue('C6', 'Jenis Transaksi')
                    ->setCellValue('D6', 'No Anggota')
                    ->setCellValue('E6', 'Nama Anggota')
                    ->setCellValue('F6', 'No Transaksi')
                    ->setCellValue('G6', 'Metode Pembayaran')
                    ->setCellValue('H6', 'Tanggal Transaksi')
                    ->setCellValue('I6', 'Status Pembayaran')
                    ->setCellValue('J6', 'Tanggal Pembayaran')
                    ->setCellValue('K6', 'Nominal')
                    ->setCellValue('L6', 'PPN')
                    ->setCellValue('M6', 'Total');
                    
        $objPHPExcel->getActiveSheet()->getStyle('A6:M3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('c2d7f3');
        $objPHPExcel->getActiveSheet()->getStyle('A6:M3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A6:M3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(34);
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
        $objPHPExcel->getActiveSheet()->setAutoFilter('B6:M6');
        $num=7;

        foreach($this->data->items as $k => $i){
            $i  = $i->transaksi;

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
        header('Content-Disposition: attachment;filename="invoice-' .date('d-M-Y') .'.xlsx"');
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
        },'invoice-' .date('d-M-Y') .'.xlsx');
    }

}
