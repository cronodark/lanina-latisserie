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
        $validated = $request->validate(
            $this->addressRules(),
            $this->addressValidationMessages(),
            $this->addressAttributes()
        );

        $validated = $this->normalizeAddressData($validated);

        auth()->user()->address()->create($validated);

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

        $validated = $this->normalizeAddressData($validated);

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

    /**
     * Aturan validasi untuk form alamat.
     * - state/city/district wajib string (diisi dari dropdown wilayah Indonesia).
     * - rt/rw hanya angka 1-3 digit (akan di-pad 0 saat disimpan).
     * - zip_code wajib 5 digit angka (dipilih dari dropdown).
     * - notes wajib diisi karena kolom DB not null.
     */
    private function addressRules(): array
    {
        return [
            'street'   => ['required', 'string', 'min:5', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'city'     => ['required', 'string', 'max:255'],
            'state'    => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'digits:5'],
            'rt'       => ['required', 'digits_between:1,3'],
            'rw'       => ['required', 'digits_between:1,3'],
            'notes'    => ['required', 'string', 'min:3', 'max:500'],
        ];
    }

    private function addressValidationMessages(): array
    {
        return [
            'required'       => 'Kolom :attribute wajib diisi.',
            'string'         => 'Kolom :attribute harus berupa teks.',
            'min'            => 'Kolom :attribute minimal :min karakter.',
            'max'            => 'Kolom :attribute maksimal :max karakter.',
            'digits'         => 'Kolom :attribute harus berupa :digits digit angka.',
            'digits_between' => 'Kolom :attribute harus berupa angka dengan panjang :min sampai :max digit.',
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

    /**
     * Normalisasi data alamat sebelum disimpan:
     * - RT/RW di-pad 0 di depan agar selalu 3 digit sesuai kolom char(3).
     *   Contoh: "7" -> "007", "15" -> "015".
     */
    private function normalizeAddressData(array $data): array
    {
        if (isset($data['rt'])) {
            $data['rt'] = str_pad($data['rt'], 3, '0', STR_PAD_LEFT);
        }
        if (isset($data['rw'])) {
            $data['rw'] = str_pad($data['rw'], 3, '0', STR_PAD_LEFT);
        }
        return $data;
    }
}
