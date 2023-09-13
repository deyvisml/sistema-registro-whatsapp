<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function send_message(Request $request)
    {
        $request->validate([
            'contact' => 'required',
            'template' => 'required',
            'parameters' => 'required',
        ]);

        $phone_number = $request->input("contact");
        $type_template = $request->input("template");
        $parameters = $request->input("parameters");

        $whatsapp_api_controller = new WhatsappAPIController;

        $response = $whatsapp_api_controller->send_message($phone_number, $type_template, $parameters);

        $data = [
            "error_occurred" => false,
            "data" => "The message was successfully send!"
        ];

        return response()->json($data, 200);
    }
}
