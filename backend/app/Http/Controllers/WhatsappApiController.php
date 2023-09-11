<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappApiController extends Controller
{
    public function enviarMensaje(){

        try{

            $token ='EAAL01VNxGRoBO0NsBARBKtjzewSy4Y5VHeoCvA0blQZAxxDZA9nNsKLyhEV4kw1qdbFndvwClMBtbDjAj4aM5bAM2kJuqcbdjLRi6TaCGZASCJG0KS2aPHs170IyyONQizGvmKZBSZBRAlr1uWIuYh1O19rGVkRcb87uQlL5qAewZAqWzsplkPB8hMhLjpxBxUJU4F8Lk5ZAqXIf7EV5HPB7isyZCWYZD';
            $phoneId = '124049360790514';
            $version = 'v17.0';
            $payload = [ 
                'messaging_product' => 'whatsapp',
                'to' => '51950127962',
                'type' => 'template',
                'template' => [
                    'name' => 'sistema_de_verificacion',
                    'language' => [
                        'code' => 'es_MX'
                    ],
                    'components' =>[
                        [
                            'type' => 'body',
                            'parameters'=>[
                                [
                                    'type' => 'text',
                                    'text' => '00000'
                                ]
                            ]
                            
                        ]
                    ]
                ]
                
            ];
            $message = Http::withToken($token)->post('https://graph.facebook.com/v17.0/'.$phoneId.'/messages',$payload)->throw()->json();
            return response()->json([
                'success' => true,
                'data' => $message,
            ], 200);
        }
        catch(Exceptio $e){
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }

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

    public function procesarWebhook(Request $request){  // this function reveived the peticion of type post (recibimos los mensajes)
        try{

            
            $bodyContent = json_decode($request->getContent(), true);
            $value = $bodyContent['entry'][0]['changes'][0]['value'];
            $body = '';

            if (!empty($value['messages'])){    // solo ejecutamos cuando nos envian un mensaje y no cuando leen el mensaje que enviamos
                if ($value['messages'][0]['type'] == 'text'){
                    $body = $value['messages'][0]['text']['body'];
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
}
