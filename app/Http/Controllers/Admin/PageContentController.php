<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageContentController extends Controller
{
    // Daftar halaman statis yang bisa dikelola. Sesuaikan dengan struktur situs.
    private const PAGE_KEYS = [
        'tentang-kami' => 'Tentang Kami',
        'perkebunan' => 'Perkebunan',
        'keberlanjutan' => 'Keberlanjutan',
    ];

    public function index(): View
    {
        $pages = PageContent::all()->keyBy('page_key');
        return view('admin.pages.index', [
            'pages' => $pages,
            'pageKeys' => self::PAGE_KEYS,
        ]);
    }

    public function edit(string $pageKey): View
    {
        abort_unless(array_key_exists($pageKey, self::PAGE_KEYS), 404);

        $page = PageContent::firstOrNew(['page_key' => $pageKey], [
            'title' => self::PAGE_KEYS[$pageKey],
        ]);

        return view('admin.pages.edit', compact('page', 'pageKey'));
    }

    public function update(Request $request, string $pageKey): RedirectResponse
    {
        abort_unless(array_key_exists($pageKey, self::PAGE_KEYS), 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $page = PageContent::firstOrNew(['page_key' => $pageKey]);

        if ($request->hasFile('image')) {
            if ($page->image) {
                Storage::disk('public')->delete($page->image);
            }
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        $page->fill($data);
        $page->page_key = $pageKey;
        $page->save();

        return redirect()->route('admin.pages.index')->with('success', 'Konten halaman berhasil diperbarui.');
    }
}
