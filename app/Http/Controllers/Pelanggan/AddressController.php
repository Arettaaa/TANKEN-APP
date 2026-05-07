<?php

namespace App\Http\Controllers\Pelanggan;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    public function index()
    {
        $addresses = auth()->user()->addresses()->orderByDesc('is_default')->get();
        return view('pelanggan.profil-alamat', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:20',
            'region' => 'required|string',
            'postal' => 'required|string|max:10',
            'street' => 'required|string',
        ]);

        // Kalau is_default true, reset semua dulu
        if ($request->is_default) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create($request->only(
            'name',
            'phone',
            'region',
            'city_id',
            'postal',
            'street',
            'details',
            'label',
            'is_default'
        ));

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function update(Request $request, Address $address)
    {
        // Pastikan alamat milik user yang login
        abort_if($address->user_id !== auth()->id(), 403);

        if ($request->is_default) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address->update($request->only(
            'name',
            'phone',
            'region',
            'city_id',
            'postal',
            'street',
            'details',
            'label',
            'is_default'
        ));

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        abort_if($address->is_default, 403, 'Alamat utama tidak bisa dihapus.');

        $address->delete();
        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Alamat utama berhasil diubah.');
    }

    public function getWilayah(Request $request)
    {
        $type = $request->type; // provinsi, kota, kecamatan, kelurahan
        $id   = $request->id;

        $apiKey = env('BINDERBYTE_API_KEY');

        $url = '';

        if ($type === 'provinsi') {
            $url = "https://api.binderbyte.com/wilayah/provinsi?api_key=$apiKey";
        }

        if ($type === 'kota') {
            $url = "https://api.binderbyte.com/wilayah/kabupaten?api_key=$apiKey&id_provinsi=$id";
        }

        if ($type === 'kecamatan') {
            $url = "https://api.binderbyte.com/wilayah/kecamatan?api_key=$apiKey&id_kabupaten=$id";
        }

        if ($type === 'kelurahan') {
            $url = "https://api.binderbyte.com/wilayah/kelurahan?api_key=$apiKey&id_kecamatan=$id";
        }

        $response = Http::get($url);

        return response()->json($response->json());
    }
}
