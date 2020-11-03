<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function storeUnit(Request $request)
    {
        $request->validate([
            'unit' => 'required|min:3|max:90|unique:units',
        ]);

        $unit = new Unit([
            'unit' => $request->unit,
        ]);
        $unit->save();
        return redirect()->back()->with('success', 'Unit Berhasil Ditambah');
    }

    public function updateUnit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3|max:90|unique:category_products',
        ]);

        $eUP = Unit::find($request->get('idU'));
        $eUP->unit = $request->get('name');
        $eUP->save();
        return redirect()->back()->with('success', 'editted');
    }
}
