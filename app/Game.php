<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Requester;

class Game extends Model
{

    public function start()
    {
        //$token = $this->getToken($request);
        //echo ( $token );
        //echo '<br><br><br>';
        //echo $this->getSet( $token );
        $r = new Requester;
        $deck = $r->getDeck();
        //var_dump ( $deck );
        echo $deck;
        $json = $r->getCardSet($deck);
        echo $json;
       /* echo 'EXAMPLE=' .
         '[
    {
        "number": "J",
        "suit": "diamonds"
    },
    {
        "number": "K",
        "suit": "clubs"
    },
    {
        "number": "K",
        "suit": "diamonds"
    },
    {
        "number": "K",
        "suit": "hearts"
    },
    {
        "number": "K",
        "suit": "spades"
    }
]';*/

        $combination = new Combination(new Set($json));
    }

    public function __getSet( $token )
    {
        $url = sprintf('http://dealer.internal.comparaonline.com:8080/deck/%s/deal/5', $token);
        $res = $this->guzzle->request('GET', $url);
        $json = (string)$res->getBody();

        echo $json;
    }

    public function __getToken($request)
    {
        if ( $request->session()->has('token') && $request->session()->get('token') ) {
            echo 'Already has';
            return $request->session()->get('token');
        } else {
            return $this->getNewToken($request);
        }
    }

    //b06ba070-8830-11e6-9c4b-b5d17f2932f5
    public function __getNewToken($request)
    {
        echo 'Gonna get new one...';
        try {
            $res = $this->guzzle->request('POST', 'http://dealer.internal.comparaonline.com:8080/deck');
            $token = (string)$res->getBody();
            $request->session()->put('token', $token);
            return $token;
        } catch(\GuzzleHttp\Exception\BadResponseException $e){
            return $this->getNewToken($request);
        }

    }
}