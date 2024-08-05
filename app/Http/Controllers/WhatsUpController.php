<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsUpController extends Controller
{
    public function SendWhats(){
      $phone='2185518783';
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.facebook.com/v20.0/407477329105710/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
    "messaging_product": "whatsapp",
    "to": '. $phone.',
    "type": "template",
    "template": {
        "name": "hello_world",
        "language": {
            "code": "en_US"
        }
    }
}',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer EAAMRnhv4tnUBO5dTgJkdrGT4uAxPnsLhYHl4TH2iTfhlsIxvx3dhm2poS936EuqAEZADsnOdOZAAOK6uHlDjublUOZBgGLN68i4DgP37BVMQEUIuekzByVLOnxBAXdN57CIrzvevlodaGp147lY2XDhDvWI1OyDnOdqE8AYK8NRLC1HqeFnyhzxqPDZBkA1hbJcS2HoMAeySEEw9wQZDZD',
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      echo $response;
    }
}
