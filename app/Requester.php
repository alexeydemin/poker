<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use League\Flysystem\Exception;

class Requester extends Model
{
    public $guzzle;


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
                $ret['error_msg'] = 0;
                $ret['body'] = (string)$res->getBody();
                break;
            case 404:
                $ret['error_msg'] = Repository::$time_expired;
                break;
            case 405:
                $ret['error_msg'] = Repository::$out_of_cards;
                break;
            case 500:
                return $this->sendRequest($request_data);
                break;
            default:
                throw new Exception('Unknown status code');
        }

        return $ret;
    }

    public function getDeck($get_new = false)
    {
        if ( Session::has('token') && Session::get('token') && !$get_new ) {
            return Session::get('token');
        } else {
            $deck = $this->sendRequest( Repository::$deckRequest )['body'];
            Session::put('token', $deck);

            return $deck;
        }
    }

    public function getCardSet($deckToken)
    {
        return $this->sendRequest( ['method' => Repository::$handRequest['method']
                                     , 'url' => sprintf( Repository::$handRequest['url'], $deckToken)] );
    }



}
