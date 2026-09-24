<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CsrCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CsrCategoryController extends Controller
{
    public function index(): View
    {
        $categories = CsrCategory::withCount('activities')->orderBy('order')->get();
        return view('admin.csr-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.csr-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        CsrCategory::create($data);

        return redirect()->route('admin.csr-categories.index')->with('success', 'Kategori CSR berhasil ditambahkan.');
    }

    public function edit(CsrCategory $csrCategory): View
    {
        $csrCategory->load('activities');
        return view('admin.csr-categories.edit', ['category' => $csrCategory]);
    }

    public function update(Request $request, CsrCategory $csrCategory): RedirectResponse
    {
        $data = $this->validateData($request);
        $csrCategory->update($data);

        return redirect()->route('admin.csr-categories.index')->with('success', 'Kategori CSR berhasil diperbarui.');
    }

    public function destroy(CsrCategory $csrCategory): RedirectResponse
    {
        foreach ($csrCategory->activities as $activity) {
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
        }

        $csrCategory->delete(); // csr_activities ikut terhapus otomatis (cascadeOnDelete)

        return redirect()->route('admin.csr-categories.index')->with('success', 'Kategori CSR berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
        ]);
    }
}