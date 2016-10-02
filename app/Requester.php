<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class Requester extends Model
{
    public $guzzle;
    public $deckPath = ['method'=>'POST', 'url' => 'http://dealer.internal.comparaonline.com:8080/deck' ];
    public $cardSetPath  = ['method'=>'GET', 'url' => 'http://dealer.internal.comparaonline.com:8080/deck/%s/deal/5' ];

    public function __construct()
    {
        $this->guzzle = new Client();
    }

    //Simple wrapper to handle 5xx error;
    private function sendRequest($request_data)
    {
        try {
            $res = $this->guzzle->request($request_data['method'], $request_data['url']);
            $body = (string)$res->getBody();
            return $body;
        } catch(\GuzzleHttp\Exception\ClientException $ce) {
            echo  'We are out of cards!!' ;
            return false;
        } catch(\GuzzleHttp\Exception\ServerException $e){
            return $this->sendRequest($request_data);
        }
    }

    public function getNewDeck()
    {
        return $this->sendRequest( $this->deckPath );
    }

    public function getDeck()
    {
        if ( Session::has('token') && Session::get('token') ) {
            return Session::get('token');
        } else {
            $deck = $this->getNewDeck();
            Session::put('token', $deck);

            return $deck;
        }
    }

    public function getCardSet($deckToken)
    {
        return $this->sendRequest( ['method' => $this->cardSetPath['method'], 'url' => sprintf( $this->cardSetPath['url'], $deckToken)] );
    }



}
