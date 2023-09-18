<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class WhatsappAPIController extends Controller
{
    public function send_message($phone_number, $template_key, $parameters)
    {
        $phone_number = '51950127962';
        //dd($phone_number);
        $type_template = 'plantillatextodos';
        // values only text (code of example. delete after)        
        $parameters = ["name_value" => "Dany", "text_value" => "mensaje de prueba", "url_value" => null];
        // values only image (code of example. delete after)
        //$parameters = ["name_value"=>"juan", "text_value"=>"mensaje de prueba", "photo_url_value"=>"https://imagen.research.google/main_gallery_images/a-brain-riding-a-rocketship.jpg"];
        // values only pdf (code of example. delete after)
        //$parameters = ["name_value"=>"juan", "text_value"=>"mensaje de prueba", "pdf_url_value"=>"https://www.ub.edu/doctorat_eapa/wp-content/uploads/2012/12/El-art%C3%ADculo-cient%C3%ADfico_aspectos-a-tener-en-cuenta.pdf"];
        //dd($parameters);
        switch ($type_template) {
            case 'plantillatextodos':
                $name_value = $parameters["name_value"];    // Juan, deyvis 
                $text_value = $parameters["text_value"];    
                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone_number,
                    'type' => 'template',
                    'template' => [
                        'name' => $type_template,   // plantillatextouno
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

            case 'plantillafotodos':
                $name_value = $parameters["name_value"];    // Juan, deyvis 
                $text_value = $parameters["text_value"];    
                $photo_url_value  = $parameters["photo_url_value"];
                $photo_name_value = 'presione para descargar';      
                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone_number,
                    'type' => 'template',
                    'template' => [
                        'name' => $type_template,
                        'language' => [
                            'code' => 'es_MX'
                        ],
                        'components' => [
                            [
                                'type' => 'header',
                                'parameters' => [
                                    [
                                        'type' => 'image',
                                        'image' => ['link' => $photo_url_value]
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

            case 'plantillapdfdos':
                $name_value = $parameters["name_value"];    // Juan, deyvis 
                $text_value = $parameters["text_value"];    // 
                $pdf_url_value = $parameters["pdf_url_value"];
                $pdf_name_value = 'presione para descargar';  
                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone_number,
                    'type' => 'template',
                    'template' => [
                        'name' => $type_template,
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
            $token ='EAAL01VNxGRoBO6B3QnUZBO4ZAUqbAiIQFSrBpBZCojIAmzjMkujmO7nAZBnmRwnjYyUFKzXd5lASQlxQbGqSHuLXjZAQJkZAqtGmQ6PXkftIhZAYNJJ1sgHCMfm3qLUZCeIHx7Qg06ef2LppvLcujdczZA9SK34Nz1gZB4idVR3FPHw4knJy4g03dJwqP53yqZCxCfFAeTmRUQQRZCJjjMhCFGVeCu2UuPQZD';
            $phoneId = '124049360790514';
            $version = 'v17.0';
            $message = Http::withToken($token)->post('https://graph.facebook.com/v17.0/'.$phoneId.'/messages',$payload)->throw()->json();
            //dd($phone_number);
        } catch (Exception $e) {
            return response()->json([
                'errorOccurred' => true,
                'data' => $message,
            ], 500);
        }

        return response()->json([
            'errorOccurred' => false,
            'data' => $message,
        ], 200);

    }

    public function verificacionwebhook(Request $request){
        try{
            $verifytoken = 'thisismyverificationtoken!!';
            $query = $request->query();

            $mode = $query['hub_mode'];
            $token = $query['hub_verify_token'];
            $challenge = $query['hub_challenge'];

            if($mode && $token){    // if both variables are empty, the condition is not executed
                if($mode === 'subscribe' && $token === $verifytoken){   // Compared between values input and values expected 
                    return response($challenge, 200)->header('Content-Type', 'text/plain'); // This message is sent to facebook
                }
            }

            throw new Exception('Invalid request'); 

        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function procesarWebhook(Request $request)
    try{

            
        $bodyContent = json_decode($request->getContent(), true);
        $value = $bodyContent['entry'][0]['changes'][0]['value'];
        $body = '';

        if (!empty($value['messages'])){    // solo ejecutamos cuando nos envian un mensaje y no cuando leen el mensaje que enviamos
            if ($value['messages'][0]['type'] == 'text'){
                //$body = $value['messages'][0]['text']['body'];
                $body = array(); // Inicializar $body como un arreglo vacío
                $body['input_message'] = $value['messages'][0]['text']['body'];
                $body['input_name'] = $value['contacts'][0]['profile']['name'];
                $body['input_phone_number'] = $value['contacts'][0]['wa_id'];
            }
        }


        return response()->json([
            'success' => true,
            'data' => $body,
            //'data' => $bodyContent,
        ], 200);

    }
    catch(Exception $e){
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}
