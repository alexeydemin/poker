<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    const NUMBER = 'number';
    const SUIT = 'suit';
    public $set;
    private $n_pics = ['A' => '1', '2' => '2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8' => '8', '9' => '9',
        '10' => 'a', 'J' => 'b', 'Q' => 'd', 'K' => 'e' ];
    private $s_pics = ['spades' => 'a', 'hearts' => 'b', 'diamonds' => 'c', 'clubs' => 'd'];


    public function __construct($json)
    {
        $this->suit_str = self::SUIT;
        $this->number_str = self::NUMBER;
        $this->set = json_decode($json);
        if( !$this->set ) die;
        $this->addPics();
        print_r( $this->set );
        if( $this->set  != array_unique($this->set, SORT_REGULAR) ){
            throw new Exception('Deck inconsistency detected!');
        }
    }

    private function addPics()
    {
        $ss = $this->suit_str;
        $ns = $this->number_str;
        foreach($this->set as $s){
            $s->unicode = '&#x1f0' . $this->s_pics[ $s->$ss ] . $this->n_pics[ $s->$ns ] ;
        }
    }

    public function getSuits()
    {

        $res = [];
        foreach( $this->set as $card ) {
            $val = $this->suit_str;
            $res[] = $card->$val;
            if( !in_array($card->$val, Combination::$suits) ){
                throw new Exception('Unknown card suit');
            }
        }
        return $res;
    }

    public function getNumbers()
    {
        $res = [];
        foreach( $this->set as $card ) {
            $val = $this->number_str;
            $res[] = $card->$val;
            if( !in_array($card->$val, Combination::$order) ){
                throw new Exception('Unknown card number');
            }
        }

        return $res;
    }
}