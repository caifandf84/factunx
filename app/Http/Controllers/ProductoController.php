<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller {

    public function index() {
        $products = \App\Producto::all();
        return view('productos.main')->with('products', $products);
    }

}
