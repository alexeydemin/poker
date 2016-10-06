<?php

namespace App;

use \GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use League\Flysystem\Exception;

class Requester
{
    private $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client();
    }


    /**
     * Get request data ['method' => [POST/GET], ''url' => 'http://hfdskjfhsdk']
     * and return array of response
     * @param $request_data
     * @return ['error_msg' => 'Bla-bla-bla', 'body' => 'Body of request']
     * @throws Exception
     */
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

    /**
     * Get token of the deck from session if not exists create new one.
     * If $get_new=true force create new one.
     * @param bool $get_new
     * @return mixed
     */
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

    /**
     * Returns result of request for particular deck token.
     * @param $deckToken
     * @return mixed
     */
    public function getCardSet($deckToken)
    {
        return $this->sendRequest( ['method' => Repository::$handRequest['method']
                                     , 'url' => sprintf( Repository::$handRequest['url'], $deckToken)] );
    }



}
