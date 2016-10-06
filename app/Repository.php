<?php

namespace App;

class Repository
{
    public static $n_parts       = ['A' => '1', '2' => '2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8' => '8', '9' => '9',
                                   '10' => 'a', 'J' => 'b', 'Q' => 'd', 'K' => 'e' ];
    public static $s_parts       = ['spades' => 'a', 'hearts' => 'b', 'diamonds' => 'c', 'clubs' => 'd'];
    public static $deckRequest  = ['method'=>'POST', 'url' => 'http://dealer.internal.comparaonline.com:8080/deck' ];
    public static $handRequest  = ['method'=>'GET', 'url' => 'http://dealer.internal.comparaonline.com:8080/deck/%s/deal/5' ];
    public static $time_expired = 'Your game time has expired. Press Shuffle button to start again.';
    public static $out_of_cards = 'We are out of cards! Press Shuffle button to start again.';


}