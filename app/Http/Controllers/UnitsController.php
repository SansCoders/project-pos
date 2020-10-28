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
}
