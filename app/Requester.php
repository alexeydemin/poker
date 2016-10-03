<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use League\Flysystem\Exception;

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
        $res = $this->guzzle->request($request_data['method'], $request_data['url'], ['http_errors' => false]);
        switch ( $res->getStatusCode() )
        {
            case 200:
                return (string)$res->getBody();
            case 404:
                echo 'Your game has expired';
                break;
            case 405:
                echo 'We are out of cards!';
                break;
            case 500:
                return $this->sendRequest($request_data);
            default:
                throw new Exception('Unknown status code');
        }
    }

    public function getNewDeck()
    {
        return $this->sendRequest( $this->deckPath );
    }

    public function getDeck($get_new = false)
    {
        if ( Session::has('token') && Session::get('token') && !$get_new ) {
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
