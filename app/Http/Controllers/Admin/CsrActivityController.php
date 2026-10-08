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

    public function edit(CsrActivity $activity): View
    {
        return view('admin.csr-activities.edit', ['activity' => $activity]);
    }

    public function update(Request $request, CsrActivity $activity): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: false);

        if ($request->hasFile('image')) {
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $data['image'] = $request->file('image')->store('csr', 'public');
        }

        $activity->update($data);

        return redirect()
            ->route('admin.csr-categories.edit', $activity->csr_category_id)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(CsrActivity $activity): RedirectResponse
    {
        $categoryId = $activity->csr_category_id;

        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();

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