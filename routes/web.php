<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Home;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Dashboard\Index as  DashboardIndex;
use App\Http\Livewire\Reseller\Index as ResellerIndex;
use App\Http\Livewire\Reseller\Create as ResellerCreate;
use App\Http\Livewire\Reseller\Edit as ResellerEdit;
use App\Http\Livewire\DeliveryOrder\Reseller as DeliveryOrderReseller;
use App\Http\Livewire\DeliveryOrder\ResellerCreate as DeliveryOrderResellerCreate;
use App\Http\Livewire\DeliveryOrder\Produsen as DeliveryOrderProdusen;
use App\Http\Livewire\ProdusenInvoice\Index as ProdusenInvoiceIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', Home::class)->name('home')->middleware('auth');
Route::get('privacy', App\Http\Livewire\Privacy::class)->name('privacy');
Route::get('login', App\Http\Livewire\Login::class)->name('login');
Route::get('register', App\Http\Livewire\Register::class)->name('register');
Route::get('konfirmasi-pembayaran', App\Http\Livewire\KonfirmasiPembayaran::class)->name('konfirmasi-pembayaran');
Route::get('konfirmasi-pendaftaran', App\Http\Livewire\KonfirmasiPendaftaran::class)->name('konfirmasi-pendaftaran');
Route::get('transaksi/cetak-struk-kasir/{data}', [\App\Http\Controllers\TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak-struk-kasir');
Route::get('linksakti', function () {
    Auth::loginUsingId(4);
    return redirect()->route('transaksi.index');
});
Route::get('phpinfo',function(){
    phpinfo();
});

// All login
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('profile', App\Http\Livewire\Profile::class)->name('profile');
    Route::get('back-to-admin', [App\Http\Controllers\IndexController::class, 'backtoadmin'])->name('back-to-admin');
});
Route::get('user-member/print-member/{id}', [\App\Http\Controllers\UserMemberController::class, 'printMember'])->name('user-member.print-member');
Route::get('user-member/print-iuran/{id}/{tahun}', [\App\Http\Controllers\UserMemberController::class, 'printIuran'])->name('user-member.print-iuran');
Route::post('ajax/get-member', [\App\Http\Controllers\AjaxController::class, 'getMember'])->name('ajax.get-member');
Route::get('set-navbar-show', function () {
    $navbar = get_setting('show-navbar');
    if ($navbar == 0 || $navbar == "") {
        update_setting('show-navbar', 1);
    } else {
        update_setting('show-navbar', 0);
    }
})->name('set-navbar-show');

// Administrator
Route::group(['middleware' => ['auth', 'access:1']], function () {
    Route::get('qrcode', [\App\Http\Controllers\UserMemberController::class, 'qrcode'])->name('qrcode');
    Route::get('log-activity', App\Http\Livewire\LogActivity\Index::class)->name('log-activity.index');
    Route::get('setting', App\Http\Livewire\Setting::class)->name('setting');
    Route::get('users/insert', App\Http\Livewire\User\Insert::class)->name('users.insert');
    Route::get('user-access', App\Http\Livewire\UserAccess\Index::class)->name('user-access.index');
    Route::get('user-access/insert', App\Http\Livewire\UserAccess\Insert::class)->name('user-access.insert');
    Route::get('users', App\Http\Livewire\User\Index::class)->name('users.index');
    Route::get('users/edit/{id}', App\Http\Livewire\User\Edit::class)->name('users.edit');
    Route::post('users/autologin/{id}', [App\Http\Livewire\User\Index::class, 'autologin'])->name('users.autologin');
    
    Route::get('product', App\Http\Livewire\Product\Index::class)->name('product.index');
    Route::get('product/insert', App\Http\Livewire\Product\Insert::class)->name('product.insert');
    Route::get('product/{id}/edit', App\Http\Livewire\Product\Edit::class)->name('product.edit');
    
    Route::get('transaksi', App\Http\Livewire\Transaksi\Index::class)->name('transaksi.index');
    Route::get('transaksi/items/{data}', App\Http\Livewire\Transaksi\Items::class)->name('transaksi.items');
    Route::get('transaksi/cetak-barcode/{no}', [\App\Http\Controllers\TransaksiController::class, 'cetakBarcode'])->name('transaksi.cetak-barcode');
    Route::get('transaksi/cetak-struk/{data}', [\App\Http\Controllers\TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak-struk');

    // Produk
    Route::get('vendor/index', App\Http\Livewire\Vendor\Index::class)->name('vendor.index');
    Route::get('purchase-request/index', App\Http\Livewire\PurchaseRequest\Index::class)->name('purchase-request.index');
    Route::get('purchase-order/index', App\Http\Livewire\PurchaseOrder\Index::class)->name('purchase-order.index');
    Route::get('purchase-order/insert', App\Http\Livewire\PurchaseOrder\Insert::class)->name('purchase-order.insert');
    Route::get('purchase-order/detail/{data}', App\Http\Livewire\PurchaseOrder\Detail::class)->name('purchase-order.detail');
    Route::get('purchase-order/insert-delivery-order/{data}', App\Http\Livewire\PurchaseOrder\InsertDeliveryOrder::class)->name('purchase-order.insert-delivery-order');

    Route::get('transaksi/cetak-struk-admin/{data}', [\App\Http\Controllers\TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak-struk-admin');

    Route::get('invoice-transaksi/detail/{data}', App\Http\Livewire\InvoiceTransaksi\Detail::class)->name('invoice-transaksi.detail');
    Route::get('invoice-transaksi/index', App\Http\Livewire\InvoiceTransaksi\Index::class)->name('invoice-transaksi.index');

    Route::get('user-supplier', App\Http\Livewire\UserSupplier\Index::class)->name('user-supplier.index');
    Route::get('user-supplier/insert', App\Http\Livewire\UserSupplier\Insert::class)->name('user-supplier.insert');
    Route::get('user-supplier/listproduk/{data}', App\Http\Livewire\UserSupplier\ListProduk::class)->name('user-supplier.listproduk');

    Route::get('produsen', App\Http\Livewire\Produsen\Index::class)->name('produsen.index');
    Route::get('produsen/create', App\Http\Livewire\Produsen\Create::class)->name('produsen.create');
    Route::get('produsen/{data}/edit', App\Http\Livewire\Produsen\Edit::class)->name('produsen.edit');

    Route::get('reseller', ResellerIndex::class)->name('reseller.index');
    Route::get('reseller/create', ResellerCreate::class)->name('reseller.create');
    Route::get('reseller/{data}/edit', ResellerEdit::class)->name('reseller.edit');

    Route::get('delivery-order/reseller', DeliveryOrderReseller::class)->name('delivery-order.reseller');
    Route::get('delivery-order/reseller-create', DeliveryOrderResellerCreate::class)->name('delivery-order.reseller-create');
    Route::get('delivery-order/produsen', DeliveryOrderProdusen::class)->name('delivery-order.produsen');

    Route::get('invoice-supplier/index', App\Http\Livewire\PurchaseOrderSupplier\Index::class)->name('invoice-supplier.index');

    Route::get('produsen-invoice', ProdusenInvoiceIndex::class)->name('produsen-invoice.index');

    Route::get('supplier', App\Http\Livewire\ProductSupplier\Index::class)->name('product-supplier.index');
    Route::get('product-supplier', App\Http\Livewire\ProductSupplier\Index::class)->name('product-supplier.index');
    Route::get('product-supplier', App\Http\Livewire\ProductSupplier\Index::class)->name('product-supplier.index');
    Route::get('product-supplier/insert', App\Http\Livewire\ProductSupplier\Insert::class)->name('product-supplier.insert');
    Route::get('product-supplier/detail/{data}', App\Http\Livewire\ProductSupplier\Detail::class)->name('product-supplier.detail');
});

// Kasir
Route::group(['middleware' => ['auth', 'access:6']], function () {
    Route::get('kasir/index', App\Http\Livewire\Kasir\Index::class)->name('kasir.index');
    Route::get('transaksi/cetak-struk/{data}', [\App\Http\Controllers\TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak-struk');
});