<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::latest('guest_key');

        if ($request->filled('search'))
            $query->where('guest_name', 'like', '%'.$request->search.'%');

        if ($request->filled('segmen'))
            $query->where('segment', $request->segmen);

        // Filter tier tidak didukung DW, diabaikan
        $customers = $query->paginate(15)->withQueryString();
        $segmens   = ['vip', 'corporate', 'leisure', 'group'];
        $tiers     = ['silver', 'gold', 'platinum'];

        return view('admin.tamu.index', compact('customers', 'segmens', 'tiers'));
    }

    public function create()
    {
        return view('admin.tamu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name'  => 'required|string|max:255',
            'nationality' => 'required|string|max:100',
            'segment'     => 'required|in:vip,corporate,leisure,group',
            'city'        => 'nullable|string|max:100',
        ]);

        Customer::create($request->only(['guest_name', 'nationality', 'segment', 'city']));

        return redirect()->route('admin.tamu.index')
            ->with('success', 'Tamu berhasil ditambahkan!');
    }

    public function show(Customer $tamu)
    {
        $tamu->load(['reservations.hotel', 'reservations.room', 'reservations.channel']);
        return view('admin.tamu.show', compact('tamu'));
    }

    public function edit(Customer $tamu)
    {
        return view('admin.tamu.edit', compact('tamu'));
    }

    public function update(Request $request, Customer $tamu)
    {
        $request->validate([
            'guest_name'  => 'required|string|max:255',
            'nationality' => 'required|string|max:100',
            'segment'     => 'required|in:vip,corporate,leisure,group',
            'city'        => 'nullable|string|max:100',
        ]);

        $tamu->update($request->only(['guest_name', 'nationality', 'segment', 'city']));

        return redirect()->route('admin.tamu.index')
            ->with('success', 'Data tamu berhasil diperbarui!');
    }

    public function destroy(Customer $tamu)
    {
        $tamu->delete();
        return redirect()->route('admin.tamu.index')
            ->with('success', 'Tamu berhasil dihapus!');
    }
}