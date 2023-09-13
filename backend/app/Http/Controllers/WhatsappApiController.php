<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class WhatsappAPIController extends Controller
{
    public function send_message($phone_number, $type_template, $parameters)
    {

        switch ($type_template) {
            case 'text':
                $name_value = $parameters["name_value"];
                $text_value = $parameters["text_value"];

                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone_number,
                    'type' => 'template',
                    'template' => [
                        'name' => 'plantillatextouno',
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
                                    ]
                                ]
                            ]
                        ]
                    ]

                ];

                break;

            default:
                # code...
                break;
        }


        try {
            $token = config("services.whatsapp.key");
            $phone_id =  config("services.whatsapp.phone_id");
            $version = config("services.whatsapp.version");

            $message = Http::withToken($token)->post('https://graph.facebook.com/' . $version . '/' . $phone_id . '/messages', $payload)->throw()->json();
        } catch (\Throwable $e) {
            return response()->json([
                'errorOccurred' => true,
                'data' => $message,
            ], 500);
        }

        return response()->json([
            'errorOccurred' => false,
            'data' => $message,
        ], 200);


        /*
        $urlImagen = 'https://imagen.research.google/main_gallery_images/a-brain-riding-a-rocketship.jpg';
        $urlPdf = 'https://www.redalyc.org/pdf/4137/413740749012.pdf';

        $tipoPlantilla = 'plantillatextouno'; // Hay tres tipos uno imagen, texto y pdf: 'plantillapdfuno'    'plantillatextouno' 'plantillafotouno'


        try {

            $token = config("services.whatsapp.key");
            $phoneId =  config("services.whatsapp.phone_id");
            $version = config("services.whatsapp.version");


            if ($tipoPlantilla == 'plantillatextouno') {
                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $celularReceptor,
                    'type' => 'template',
                    'template' => [
                        'name' => 'plantillatextouno',
                        'language' => [
                            'code' => 'es_MX'
                        ],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $nombreReceptor
                                    ]
                                ]
                            ]
                        ]
                    ]

                ];
            } elseif ($tipoPlantilla == 'plantillafotouno') {

                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $celularReceptor,
                    'type' => 'template',
                    'template' => [
                        'name' => 'plantillafotouno',
                        'language' => [
                            'code' => 'es_MX'
                        ],
                        'components' => [
                            [
                                'type' => 'header',
                                'parameters' => [
                                    [
                                        'type' => 'image',
                                        'image' => ['link' => $urlImagen]
                                    ]
                                ],
                            ],

                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $nombreReceptor
                                    ]
                                ]
                            ]
                        ]
                    ]

                ];
            } elseif ($tipoPlantilla == 'plantillapdfuno') {
                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $celularReceptor,
                    'type' => 'template',
                    'template' => [
                        'name' => 'plantillapdfuno',
                        'language' => [
                            'code' => 'es_MX'
                        ],
                        'components' => [
                            [
                                'type' => 'header',
                                'parameters' => [
                                    [
                                        'type' => 'document',
                                        'document' => ['link' => $urlPdf] // http(s)://URL'
                                    ]
                                ],
                            ],

                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $nombreReceptor
                                    ]
                                ]
                            ]
                        ]
                    ]

                ];
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'ingrese el nombre de la plantilla correctamente',
                ], 400);
            }






            $message = Http::withToken($token)->post('https://graph.facebook.com/v17.0/' . $phoneId . '/messages', $payload)->throw()->json();
            return response()->json([
                'success' => true,
                'data' => $message,
            ], 200);
        } catch (Exceptio $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }

        */
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
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function procesarWebhook(Request $request)
    {  // this function reveived the peticion of type post (recibimos los mensajes)
        try {


            $bodyContent = json_decode($request->getContent(), true);
            $value = $bodyContent['entry'][0]['changes'][0]['value'];
            $body = '';

            if (!empty($value['messages'])) {    // solo ejecutamos cuando nos envian un mensaje y no cuando leen el mensaje que enviamos
                if ($value['messages'][0]['type'] == 'text') {
                    $body = $value['messages'][0]['text']['body'];
                }
            }


            return response()->json([
                'success' => true,
                'data' => $body,
                //'data' => $bodyContent,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
