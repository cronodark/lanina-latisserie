<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->address()->get();
        return view('pages.address.index', [
            'title' => 'Alamat Saya',
            'addresses' => $addresses,
        ]);
    }

    public function create()
    {
        return view('pages.address.create', [
            'title' => 'Tambah Alamat',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            $this->addressRules(),
            $this->addressValidationMessages(),
            $this->addressAttributes()
        );

        auth()->user()->address()->create($request->all());

        return redirect()->route('profile.address.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $address = auth()->user()->address()->findOrFail($id);
        return view('pages.address.edit', [
            'title' => 'Edit Alamat',
            'address' => $address,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            $this->addressRules(),
            $this->addressValidationMessages(),
            $this->addressAttributes()
        );

        $address = auth()->user()->address()->findOrFail($id);
        $address->fill($validated);

        if (! $address->isDirty()) {
            return redirect()->route('profile.address.index')->with('info', 'Tidak ada perubahan pada alamat.');
        }

        $address->save();

        return redirect()->route('profile.address.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $address = auth()->user()->address()->findOrFail($id);
        $address->delete();

        return redirect()->route('profile.address.index')->with('success', 'Alamat berhasil dihapus.');
    }

    private function addressRules(): array
    {
        return [
            'street' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'notes' => 'nullable|string|max:500',
        ];
    }

    private function addressValidationMessages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'max' => 'Kolom :attribute maksimal :max karakter.',
        ];
    }

    private function addressAttributes(): array
    {
        return [
            'street' => 'alamat jalan',
            'district' => 'kecamatan',
            'city' => 'kabupaten',
            'state' => 'provinsi',
            'zip_code' => 'kode pos',
            'rt' => 'RT',
            'rw' => 'RW',
            'notes' => 'patokan/keterangan',
        ];
    }
}
