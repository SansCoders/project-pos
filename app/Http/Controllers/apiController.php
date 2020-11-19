<?php

namespace App\Http\Controllers;

use App\Prices_Custom;
use App\Product;
use Illuminate\Http\Request;

class apiController extends Controller
{

    public function getApiDetailsProduct(Request $request)
    {
        $data = Product::find($request->idproduct);
        return response()->json($data);
    }

    public function getApiChangePriceCustomProduct(Request $request)
    {
        $price = Prices_Custom::find($request->pcid);
        if ($price == null) return response()->json("null");

        $data = Product::where('id', $price->product_id)->first();

        return response()->json([$price, $data]);
    }
}
