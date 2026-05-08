<?php

namespace App\Http\Controllers;

use App\Models\TanggalTersedia;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    /**
     * Tampilkan daftar slot preorder.
     * Di-pass ke view: $slots (Collection), $totalSlot, $slotAktif, $slotPenuh.
     */
    public function index()
    {
        // Urutkan dari tanggal terdekat. Gunakan get() agar accessor bisa jalan.
        $slots = TanggalTersedia::orderBy('tanggal')->get();

        $totalSlot = $slots->count();
        $slotAktif = $slots->filter(fn ($s) => $s->status === 'Aktif')->count();
        $slotPenuh = $slots->filter(fn ($s) => $s->status === 'Penuh')->count();

        return view('pages.jadwal-admin.slot', compact(
            'slots', 'totalSlot', 'slotAktif', 'slotPenuh'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date|unique:tanggal_tersedia,tanggal',
            'kuota'      => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        TanggalTersedia::create([
            'tanggal'    => $request->tanggal,
            'kuota'      => $request->kuota,
            'keterangan' => $request->keterangan,
            'is_aktif'   => true,
        ]);

        return back()->with('success', 'Slot berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['kuota' => 'required|integer|min:1']);

        TanggalTersedia::findOrFail($id)->update(['kuota' => $request->kuota]);

        return back()->with('success', 'Slot berhasil diperbarui.');
    }

    public function destroy($id)
    {
        TanggalTersedia::findOrFail($id)->delete();

        return back()->with('success', 'Slot berhasil dihapus.');
    }

    public function toggle($id)
    {
        $slot = TanggalTersedia::findOrFail($id);
        $slot->update(['is_aktif' => !$slot->is_aktif]);

        return back()->with('success', 'Status slot diperbarui.');
    }
}
