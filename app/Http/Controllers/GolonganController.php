<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Golongan;

class GolonganController extends Controller
{
    /**
     * Menampilkan daftar semua golongan.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $golongans = Golongan::all();
        return view('content.golongan.table-golongan', compact('golongans'));
    }

    /**
     * Menampilkan formulir untuk membuat golongan baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('golongan.create');
    }

    /**
     * Menyimpan golongan baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'golongan' => 'required',
            'honor' => 'required',
            'pph' => 'required',
        ]);

        Golongan::create($request->all());

        return redirect()->route('golongan.index')
            ->with('success', 'Golongan berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit golongan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $golongan = Golongan::findOrFail($id);
        return view('content.golongan.edit-golongan', compact('golongan'));
    }

    /**
     * Memperbarui golongan di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'golongan' => 'required',
            'honor' => 'required',
            'pph' => 'required',
        ]);

        $golongan = Golongan::findOrFail($id);
        $golongan->update($request->all());

        return response()->json(['success' => true, 'message' => 'Golongan berhasil diperbarui.']);
    }

    /**
     * Menghapus golongan dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $golongan = Golongan::findOrFail($id);
        $golongan->delete();

        return redirect()->route('golongan.index')
            ->with('success', 'Golongan berhasil dihapus.');
    }
}
