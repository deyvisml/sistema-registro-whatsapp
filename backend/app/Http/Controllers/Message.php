<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Message extends Controller
{
    public function store(Request $request)
    {

        return ["response" => $request->all()];
    }
}
