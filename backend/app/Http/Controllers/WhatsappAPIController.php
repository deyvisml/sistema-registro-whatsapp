<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Support\Facades\Http;

class WhatsappAPIController extends Controller
{
    public function send_message($phone_number, $template_key, $parameters)
    {
        // text, image, pdf

        switch ($template_key) {
            case 'text':
                $name_value = $parameters["name_value"];    // Juan, deyvis 
                $text_value = $parameters["text_value"];
                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone_number,
                    'type' => 'template',
                    'template' => [
                        'name' => "plantillatextodos",
                        'language' => [
                            'code' => 'es_MX'
                        ],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $name_value
                                        //'text' => [$name_value, 'hay_camaras']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $text_value
                                        //'text' => [$name_value, 'hay_camaras']
                                    ]
                                ]
                            ]
                        ]
                    ]

                ];
                break;

            case 'image':
                $name_value = $parameters["name_value"];    // Juan, deyvis 
                $text_value = $parameters["text_value"];
                $image_url_value  = $parameters["image_url_value"];
                $photo_name_value = 'presione para descargar';

                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone_number,
                    'type' => 'template',
                    'template' => [
                        'name' => "plantillafotodos",
                        'language' => [
                            'code' => 'es_MX'
                        ],
                        'components' => [
                            [
                                'type' => 'header',
                                'parameters' => [
                                    [
                                        'type' => 'image',
                                        'image' => ['link' => $image_url_value]
                                    ]
                                ],
                            ],

                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $name_value
                                        //'text' => [$name_value, 'segundo_valor']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $text_value
                                        //'text' => [$name_value, 'hay_camaras']
                                    ]
                                ]
                            ]
                        ]
                    ]

                ];
                break;

            case 'pdf':
                $name_value = $parameters["name_value"];    // Juan, deyvis 
                $text_value = $parameters["text_value"];    // 
                $pdf_url_value = $parameters["pdf_url_value"];
                $pdf_name_value = 'presione para descargar';
                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone_number,
                    'type' => 'template',
                    'template' => [
                        'name' => "plantillapdfdos",
                        'language' => [
                            'code' => 'es_MX'
                        ],
                        'components' => [
                            [
                                'type' => 'header',
                                'parameters' => [
                                    [
                                        'type' => 'document',
                                        'document' => ['link' => $pdf_url_value, 'filename' => $pdf_name_value] // http(s)://URL'
                                    ]
                                ],
                            ],

                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $name_value
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $text_value

                                    ]
                                ]
                            ]
                        ]
                    ]

                ];
                break;
            default:
                return response()->json([
                    'success' => false,
                    'error' => 'ingrese el nombre de la plantilla correctamente',
                ], 400);
                break;
        }


        try {
            $token = config("services.whatsapp.key");
            $phone_id =  config("services.whatsapp.phone_id");
            $version = config("services.whatsapp.version");

            Http::withToken($token)->post('https://graph.facebook.com/' . $version . '/' . $phone_id . '/messages', $payload)->throw()->json();
        } catch (\Throwable $e) {
            return response()->json([
                'error_occurred' => true,
                'data' => ["message" => "An error occurred sending the message :("],
            ], 200);
        }

        return response()->json([
            'error_occurred' => false,
            'data' => ["message" => "The message was successfully send :)"],
        ], 200);
    }

    public function verificacionwebhook(Request $request)
    {
        try {
            $verifytoken = 'thisismyverificationtoken!!';
            $query = $request->query();

            $mode = $query['hub_mode'];
            $token = $query['hub_verify_token'];
            $challenge = $query['hub_challenge'];

            if ($mode && $token) {    // if both variables are empty, the condition is not executed
                if ($mode === 'subscribe' && $token === $verifytoken) {   // Compared between values input and values expected 
                    return response($challenge, 200)->header('Content-Type', 'text/plain'); // This message is sent to facebook
                }
            }

            throw new Exception('Invalid request');
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function received_message(Request $request)
    {  // this function reveived the peticion of type post (recibimos los mensajes)
        try {
            $bodyContent = json_decode($request->getContent(), true);
            $value = $bodyContent['entry'][0]['changes'][0]['value'];
            $body = '';

            if (!empty($value['messages'])) {    // solo ejecutamos cuando nos envian un mensaje y no cuando leen el mensaje que enviamos
                if ($value['messages'][0]['type'] == 'text') {

                    $phone_number = $value['contacts'][0]['wa_id'];
                    $alias = $value['contacts'][0]['profile']['name'];
                    $received_at = time();
                    $message = $value['messages'][0]['text']['body'];
                    $type = 1;

                    Message::create([
                        "phone_number" => $phone_number,
                        "alias" => $alias,
                        "received_at" => $received_at,
                        "message" => $message,
                        "type" => $type,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $body,
                //'data' => $bodyContent,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
