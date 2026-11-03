<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function index()
    {
        return view('go.settings.divisions.index', [
            'divisions' => Division::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['nama' => trim((string) $request->nama)]);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('divisions', 'nama')],
        ], [
            'nama.required' => 'Nama divisi wajib diisi.',
            'nama.unique' => 'Divisi dengan nama itu sudah ada.',
        ]);

        Division::create($data);

        return redirect()->route('go.settings.divisions.index')
            ->with('success', "Divisi {$data['nama']} ditambahkan.");
    }

    public function edit(Division $division)
    {
        return view('go.settings.divisions.edit', ['division' => $division]);
    }

    public function update(Request $request, Division $division)
    {
        $request->merge(['nama' => trim((string) $request->nama)]);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('divisions', 'nama')->ignore($division->id)],
        ], [
            'nama.required' => 'Nama divisi wajib diisi.',
            'nama.unique' => 'Divisi dengan nama itu sudah ada.',
        ]);

        $division->update($data);

        return redirect()->route('go.settings.divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->route('go.settings.divisions.index')->with('success', 'Divisi dihapus dari daftar.');
    }
}