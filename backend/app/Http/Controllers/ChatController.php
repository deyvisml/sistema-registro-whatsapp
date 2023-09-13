<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsappAPIController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'contact' => 'required',
            'template' => 'required',
            'message' => 'required',
        ]);

        $phone_number = $request->input("contact");
        $type_template = $request->input("template");
        $parameters = $request->input("message");

        $whatsapp_api_controller = new WhatsappAPIController;

        $response = $whatsapp_api_controller->send_message($phone_number, $type_template, $parameters);

        $data = [
            "error_occurred" => false,
            "data" => "The message was successfully send!"
        ];

        return response()->json($data, 200);
    }
}
