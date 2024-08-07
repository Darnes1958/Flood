<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsUpController extends Controller
{
    public function SendWhats(){
      $phone='218925518783';
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
          'Authorization: Bearer EAAMRnhv4tnUBO4pQJQuc9uDYWiHzZBkzykc0euiwaygVsHbwXWU7STK4yboH3WdZAoRSt4KUv5eseYyv4kZCjyzhZCZAQMIraKZC4szloZCqMrvpRApjEwpRbZBgkGGWSzdK09hTf2yCIcIHZCZBCUdD2AXjc7WzNWZA6HnQK3q9zgbyM8KTsrWpq0zLH5CVBWqSDdyBtIoZAcyvVgkmEzU8',
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      echo $response;
    }
}
