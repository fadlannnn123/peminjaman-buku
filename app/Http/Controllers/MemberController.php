<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Anggota::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            })->orWhere('alamat', 'like', "%{$search}%");
        }

        $members = $query->latest()->paginate(10);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        // Only show students who don't have a member record
        $users = User::where('role', 'siswa')
            ->whereDoesntHave('anggota')
            ->get();

        return view('admin.members.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:anggota,user_id',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
        ]);

        Anggota::create($request->only('user_id', 'alamat', 'no_telepon'));

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function edit(Anggota $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Anggota $member)
    {
        $request->validate([
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
        ]);

        $member->update($request->only('alamat', 'no_telepon'));

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil diupdate!');
    }

    public function destroy(Anggota $member)
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus!');
    }
}
