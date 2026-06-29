<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $units = [];

        if (!empty($cart)) {
            $unitIds = array_keys($cart);
            $units = Unit::with('category')->whereIn('id', $unitIds)->get()->keyBy('id');
        }

        return view('cart.index', compact('units', 'cart'));
    }

    public function add(Request $request, Unit $unit)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$unit->id])) {
            return back()->with('error', 'Unit sudah ada di keranjang.');
        }

        $cart[$unit->id] = [
            'id' => $unit->id,
            'name' => $unit->name,
            'price' => $unit->price_per_day,
            'photo' => $unit->photos[0] ?? null,
            'quantity' => 1,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Berhasil ditambahkan ke keranjang.');
    }

    public function remove(Unit $unit)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$unit->id])) {
            unset($cart[$unit->id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Unit dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
