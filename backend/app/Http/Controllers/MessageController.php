<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::orderBy("created_at", "DESC")->get();

        $data = [
            "error_occurred" => false,
            "data" => ["messages" => $messages]
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $phone_number = $request->input("phone_number");
        $alias = $request->input("alias");
        $received_at = $request->input("received_at");
        $message = $request->input("message");
        $type = $request->input("type");

        $message = Message::create([
            "phone_number" => $phone_number,
            "alias" => $alias,
            "received_at" => $received_at,
            "message" => $message,
            "type" => $type,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
