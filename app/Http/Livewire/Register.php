<?php

namespace App\Http\Livewire;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use App\Models\Reseller;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\UserMember;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Register extends Component
{
	use WithFileUploads;

	protected $listeners = ['onProvinsiChanged', 'onKabupatenChanged', 'onKecamatanChanged', 'onKelurahanChanged'];

	public $provinsi;
	public $kabupaten;
	public $kecamatan;
	public $kelurahan;

	public $username;
	public $password;
	public $passwordConfirmation;
	public $fullname;
	public $email;
	public $telepon;
	public $ktp;

	public $resellerName;
	public $kelurahanId;
	public $alamat;
	public $storePhoto;

	protected $rules = [
		'username' => 'required',
		'password' => 'required',
		'passwordConfirmation' => 'required|same:password',
		'fullname' => 'required',
		'email' => 'required',
		'telepon' => 'required',
		'ktp' => 'required',

		'resellerName' => 'required',
		'kelurahanId' => 'required',
		'alamat' => 'required',
		'storePhoto' => 'required',
	];

	public function mount()
	{
		$this->provinsi = Provinsi::orderBy('nama', 'ASC')->get();
		$this->kabupaten = [];
		$this->kecamatan = [];
		$this->kelurahan = [];
	}

	public function render()
	{
		return view('livewire.register')->layout('layouts.auth');
	}

	public function onProvinsiChanged($provinsiId)
	{
		Log::info("Provinsi ID: " . $provinsiId);
		$this->kabupaten = Kabupaten::where('provinsi_id', $provinsiId)->orderBy('nama', 'ASC')->get();
		Log::info("Kabupaten: " . json_encode($this->kabupaten));
		$this->kecamatan = [];
		$this->kelurahan = [];
	}

	public function onKabupatenChanged($kabupatenId)
	{
		Log::info("Kabupaten ID: " . $kabupatenId);
		$this->kecamatan = Kecamatan::where('kabupaten_id', $kabupatenId)->orderBy('nama', 'ASC')->get();
		Log::info("Kecamatan: " . json_encode($this->kecamatan));
		$this->kelurahan = [];
	}

	public function onKecamatanChanged($kecamatanId)
	{
		Log::info("Kecamatan ID: " . $kecamatanId);
		$this->kelurahan = Kelurahan::where('kecamatan_id', $kecamatanId)->orderBy('nama', 'ASC')->get();
		Log::info("Kelurahan: " . json_encode($this->kelurahan));
	}

	public function onKelurahanChanged($kelurahanId)
	{
		Log::info("Kelurahan ID: " . $kelurahanId);
		$this->kelurahanId = $kelurahanId;
	}

	public function submit()
	{
		$this->validate();

		$user = User::create([
			'name' => $this->fullname,
			'email' => $this->email,
			'telepon' => $this->telepon,
			'username' => $this->username,
			'password' => Hash::make($this->password),
			'ktp' => $this->ktp->store('user_ktp')
		]);

		Reseller::create([
			'user_id' => $user->id,
			'kelurahan_id' => $this->kelurahanId,
			'nama' => $this->resellerName,
			'alamat' => $this->alamat,
			'store_photo' => $this->storePhoto->store('store_photo'),
		]);

		session()->flash('message-success', __('Data Berhasil disimpan'));
		return redirect()->to('login');
	}
}
