<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $books = $query->latest()->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.books.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'nullable|exists:kategori,id_kategori',
            'foto' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('buku', 'public');
        }

        Buku::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Buku $book)
    {
        $kategoris = Kategori::all();
        return view('admin.books.edit', compact('book', 'kategoris'));
    }

    public function update(Request $request, Buku $book)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'nullable|exists:kategori,id_kategori',
            'foto' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('foto')) {
            if ($book->foto) {
                Storage::disk('public')->delete($book->foto);
            }
            $validated['foto'] = $request->file('foto')->store('buku', 'public');
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy(Buku $book)
    {
        if ($book->foto) {
            Storage::disk('public')->delete($book->foto);
        }
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }
}
