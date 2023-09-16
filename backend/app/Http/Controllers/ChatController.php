<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function send_message(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'template_id' => 'required|exists:templates,id',
        ]);

        $phone_number = $request->input("phone_number");
        $template_id = $request->input("template_id");
        $template = Template::find($template_id);

        $parameters = array();

        switch ($template->key) {
            case 'text':
                $request->validate([
                    'name_value' => 'required',
                    'text_value' => 'required',
                ]);
                $parameters["name_value"] = $request->input("name_value");
                $parameters["text_value"] = $request->input("text_value");
                break;
            case 'image':
                $request->validate([
                    'name_value' => 'required',
                    'image_url_value' => 'required',
                    'text_value' => 'required',
                ]);
                $parameters["name_value"] = $request->input("name_value");
                $parameters["image_value"] = $request->input("image_value");
                $parameters["text_value"] = $request->input("text_value");
                break;
            case 'pdf':
                $request->validate([
                    'name_value' => 'required',
                    'pdf_url_value' => 'required',
                    'text_value' => 'required',
                ]);
                break;
            default:
                $data = [
                    "error_occurred" => true,
                    "data" => "The selected template didn't exists!"
                ];

                return response()->json($data, 500);
                break;
        }


        $whatsapp_api_controller = new WhatsappAPIController;

        $response = $whatsapp_api_controller->send_message($phone_number, $template->key, $parameters);
        //dd($response);

        return $response;
    }
}
