<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CsrActivity;
use App\Models\CsrCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CsrActivityController extends Controller
{
    public function create(CsrCategory $csrCategory): View
    {
        return view('admin.csr-activities.create', ['category' => $csrCategory]);
    }

    public function store(Request $request, CsrCategory $csrCategory): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('csr', 'public');
        }

        $csrCategory->activities()->create($data);

        return redirect()
            ->route('admin.csr-categories.edit', $csrCategory)
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    // Shallow route: tidak butuh {csrCategory} di URL edit/update/destroy
    public function edit(CsrActivity $csrActivity): View
    {
        return view('admin.csr-activities.edit', ['activity' => $csrActivity]);
    }

    public function update(Request $request, CsrActivity $csrActivity): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: false);

        if ($request->hasFile('image')) {
            if ($csrActivity->image) {
                Storage::disk('public')->delete($csrActivity->image);
            }
            $data['image'] = $request->file('image')->store('csr', 'public');
        }

        $csrActivity->update($data);

        return redirect()
            ->route('admin.csr-categories.edit', $csrActivity->csr_category_id)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(CsrActivity $csrActivity): RedirectResponse
    {
        $categoryId = $csrActivity->csr_category_id;

        if ($csrActivity->image) {
            Storage::disk('public')->delete($csrActivity->image);
        }

        $csrActivity->delete();

        return redirect()
            ->route('admin.csr-categories.edit', $categoryId)
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    private function validateData(Request $request, bool $isCreate): array
    {
        return $request->validate([
            'date' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'story' => ['required', 'string'],
            'image' => [$isCreate ? 'required' : 'nullable', 'image', 'max:5000'],
            'order' => ['nullable', 'integer'],
        ]);
    }
}