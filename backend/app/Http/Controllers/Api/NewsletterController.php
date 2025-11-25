<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function index()
    {
        return response()->json(Newsletter::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:newsletter_suscriptores,email'
        ]);

        return response()->json([
            'message' => 'Suscripción registrada correctamente.',
            'data' => Newsletter::create($data)
        ], 201);
    }
}
