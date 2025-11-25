<?php

namespace App\Http\Controllers\Api;

use App\Models\Promocion;
use App\Http\Controllers\Controller;

class PromocionController extends Controller
{
    public function index()
    {
        return response()->json(Promocion::all());
    }
}
