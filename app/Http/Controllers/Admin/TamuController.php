<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('bookings')->latest();

        if ($request->filled('search'))
            $query->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');

        if ($request->filled('segmen'))
            $query->where('segmen', $request->segmen);

        if ($request->filled('tier'))
            $query->where('tier', $request->tier);

        $customers = $query->paginate(15)->withQueryString();
        $segmens   = ['vip','corporate','leisure','group'];
        $tiers     = ['silver','gold','platinum'];

        return view('admin.tamu.index', compact('customers','segmens','tiers'));
    }

    public function create()
    {
        return view('admin.tamu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:customers',
            'telepon'   => 'nullable|string|max:20',
            'segmen'    => 'required|in:vip,corporate,leisure,group',
            'negara'    => 'required|string|max:100',
            'kota_asal' => 'nullable|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'tier'      => 'required|in:silver,gold,platinum',
        ]);

        Customer::create($request->all());

        return redirect()->route('admin.tamu.index')
            ->with('success', 'Tamu berhasil ditambahkan!');
    }

    public function show(Customer $tamu)
    {
        $tamu->load(['bookings.hotel','bookings.room','bookings.channel']);
        return view('admin.tamu.show', compact('tamu'));
    }

    public function edit(Customer $tamu)
    {
        return view('admin.tamu.edit', compact('tamu'));
    }

    public function update(Request $request, Customer $tamu)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:customers,email,'.$tamu->id,
            'telepon'   => 'nullable|string|max:20',
            'segmen'    => 'required|in:vip,corporate,leisure,group',
            'negara'    => 'required|string|max:100',
            'kota_asal' => 'nullable|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'tier'      => 'required|in:silver,gold,platinum',
        ]);

        $tamu->update($request->all());

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