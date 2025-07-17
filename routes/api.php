<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'cors', 'json.response'], function(){
	
	Route::post('refresh', [\App\Http\Controllers\Api\UserController::class,'refresh']);
	Route::post('auth-login', [\App\Http\Controllers\Api\UserController::class,'login']);
	Route::post('submit-pendaftaran',[\App\Http\Controllers\Api\UserController::class,'submitPendaftaran'])->name('api.submit-pendaftaran');
	Route::post('find-no-ktp',[\App\Http\Controllers\Api\UserController::class,'findNoKtp'])->name('api.find-no-ktp');
	Route::post('konfirmasi-pendaftaran',[\App\Http\Controllers\Api\UserController::class,'konfirmasiPendaftaran'])->name('api.konfirmasi-pendaftaran');
	Route::get('get-bank',[\App\Http\Controllers\Api\IuranController::class,'get_bank'])->name('api.get-bank');

	// Integrasi Coopzone
	Route::post('transaction-store-pulsa',[\App\Http\Controllers\Api\TransactionController::class,'storePulsa']);
	Route::post('transaction-update',[\App\Http\Controllers\Api\TransactionController::class,'update']);
	Route::post('transaction/detail',[\App\Http\Controllers\Api\TransactionController::class,'detail']);
	Route::post('transaction/submit-qrcode',[\App\Http\Controllers\Api\TransactionController::class,'submitQrcode']);
	Route::post('transaction/data',[\App\Http\Controllers\Api\TransactionController::class,'data']);
	Route::post('pembiayaan-store',[\App\Http\Controllers\Api\PinjamanController::class,'store']);
	Route::post('product/update',[\App\Http\Controllers\Api\ProductController::class,'update']);
	Route::post('get-product',[\App\Http\Controllers\Api\ProductController::class,'data'])->name('api.product.data');
	Route::get('product/index',[\App\Http\Controllers\Api\ProductController::class,'index'])->name('api.product.index');
	Route::post('product/store',[\App\Http\Controllers\Api\ProductController::class,'store'])->name('api.product.store');

	Route::post('get-anggota',[\App\Http\Controllers\Api\UserMemberController::class,'data'])->name('api.anggota.data');
	Route::post('store-transaction',[\App\Http\Controllers\Api\TransactionController::class,'store'])->name('api.transaction.store');
	Route::post('sync',[\App\Http\Controllers\Api\SyncController::class,'sync'])->name('api.sync');
});

Route::group(['middleware' => 'auth:api'], function(){
	Route::get('user/check-token',[\App\Http\Controllers\Api\UserController::class,'checkToken']);
	Route::post('user/upload-photo',[\App\Http\Controllers\Api\UserController::class,'uploadPhoto'])->name('api.user.upload-photo');
	Route::post('user/change-password',[\App\Http\Controllers\Api\UserController::class,'changePassword'])->name('api.user.change-password');
	Route::get('iuran',[\App\Http\Controllers\Api\IuranController::class,'iuran'])->name('api.iuran');
	Route::get('iuran/get-last',[\App\Http\Controllers\Api\IuranController::class,'getLast'])->name('api.iuran.get-last');
	Route::post('iuran/store',[\App\Http\Controllers\Api\IuranController::class,'store'])->name('api.iuran.store');
	
	Route::get('tagihan/tunai',[\App\Http\Controllers\Api\TagihanController::class,'tagihanTunai'])->name('tagihan.tunai');
	Route::get('tagihan/first',[\App\Http\Controllers\Api\TagihanController::class,'tagihanFirst'])->name('tagihan.first');
	Route::get('pinjaman/kuota',[\App\Http\Controllers\Api\PinjamanController::class,'kuota'])->name('pinjaman.kuota');
	Route::get('simpanan/pokok',[\App\Http\Controllers\Api\SimpananController::class,'pokok'])->name('simpanan.pokok');
	Route::get('simpanan/pokok-status',[\App\Http\Controllers\Api\SimpananController::class,'pokokStatus'])->name('simpanan.pokok-status');
	Route::get('simpanan/wajib',[\App\Http\Controllers\Api\SimpananController::class,'wajib'])->name('simpanan.wajib');
	Route::get('simpanan/wajib-status',[\App\Http\Controllers\Api\SimpananController::class,'wajibStatus'])->name('simpanan.wajib-status');
	Route::get('simpanan/sukarela',[\App\Http\Controllers\Api\SimpananController::class,'sukarela'])->name('simpanan.sukarela');
	Route::get('simpanan/lainnya',[\App\Http\Controllers\Api\SimpananController::class,'lainnya'])->name('simpanan.lainnya');
	Route::post('simpanan/store',[\App\Http\Controllers\Api\SimpananController::class,'store'])->name('simpanan.store');
	Route::get('notification/data',[\App\Http\Controllers\Api\NotificationController::class,'data'])->name('notification.data');
	Route::get('get-bank',[\App\Http\Controllers\Api\BankController::class,'data'])->name('notification.data');
});