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
        $template = $request->input("template");
        $message = $request->input("message");

        // call api to send message
        /*
        switch ($template) {
            case 'text':

                break;
            case 'image':

                break;
            default:
                # code...
                break;
        }*/

        $data = [
            "error_occurred" => false,
            "data" => "The message was successfully send!"
        ];

        return response()->json($data, 200);
    }
}
