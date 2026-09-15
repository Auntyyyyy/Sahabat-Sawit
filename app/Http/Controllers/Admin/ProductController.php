<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::orderBy('order')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: true);
        $data['manfaat'] = $this->parseManfaat($request->input('manfaat'));
        $data['spesifikasi'] = $this->parseSpesifikasi(
            $request->input('spesifikasi_key', []),
            $request->input('spesifikasi_value', [])
        );
        
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        $data['image'] = $request->file('image')->store('products', 'public');

        $product = Product::create($data);

        $this->syncGalleryImage($request, $product, 'image_2', 1);
        $this->syncGalleryImage($request, $product, 'image_3', 2);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: false, ignoreId: $product->id);
        $data['manfaat'] = $this->parseManfaat($request->input('manfaat'));
        $data['spesifikasi'] = $this->parseSpesifikasi(
            $request->input('spesifikasi_key', []),
            $request->input('spesifikasi_value', [])
        );

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        $this->syncGalleryImage($request, $product, 'image_2', 1);
        $this->syncGalleryImage($request, $product, 'image_3', 2);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $product->delete(); // product_media ikut terhapus otomatis (cascadeOnDelete)

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function validateData(Request $request, bool $isCreate = false, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'detail' => ['nullable', 'string'],
            'raw_material' => ['nullable', 'string', 'max:255'],
            'product_form' => ['nullable', 'string', 'max:255'],
            'usage' => ['nullable', 'string', 'max:255'],
            'image' => [$isCreate ? 'required' : 'nullable', 'image', 'max:5000'],
            'image_2' => ['nullable', 'image', 'max:5000'],
            'image_3' => ['nullable', 'image', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
        ]);
    }

    // Textarea "satu manfaat per baris" -> array string
    private function parseManfaat(?string $raw): array
    {
        if (!$raw) {
            return [];
        }

        return collect(explode("\n", $raw))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    // Pasangan input spesifikasi_key[] & spesifikasi_value[] -> array asosiatif
    private function parseSpesifikasi(array $keys, array $values): array
    {
        $result = [];

        foreach ($keys as $i => $key) {
            $key = trim($key);
            $value = trim($values[$i] ?? '');

            if ($key !== '' && $value !== '') {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    // Bikin slug otomatis dari nama produk, tambahkan angka kalau sudah ada yang sama
    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    // Simpan/replace 1 gambar galeri di slot tertentu (order tetap: 1 = image_2, 2 = image_3)
    private function syncGalleryImage(Request $request, Product $product, string $field, int $order): void
    {
        if (!$request->hasFile($field)) {
            return;
        }

        $existing = $product->media()->where('order', $order)->first();
        $path = $request->file($field)->store('products/media', 'public');

        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
            $existing->update(['file_path' => $path]);
        } else {
            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'image',
                'file_path' => $path,
                'order' => $order,
            ]);
        }
    }
}