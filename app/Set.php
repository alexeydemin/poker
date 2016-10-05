<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Exception;

class Set
{
    const NUMBER = 'number';
    const SUIT = 'suit';
    public $set; //array of cards


    public function __construct($json)
    {
        $this->set = json_decode($json);
        if( !$this->set )
            throw new Exception('Trying to get Set from empty array');
        $this->addPics();
        if( $this->set  != array_unique($this->set, SORT_REGULAR) ){
            throw new Exception('Deck inconsistency detected!');
        }
    }

    private function addPics()
    {
        $ss = self::SUIT;
        $ns = self::NUMBER;
        foreach($this->set as $s){
            $s->unicode = '&#x1f0' . Repository::$s_pics[ $s->$ss ] . Repository::$n_pics[ $s->$ns ] . ';' ;
            $s->color = in_array($s->$ss, ['hearts', 'diamonds']) ? 'red' : 'black';
        }
    }

    public function getSuits()
    {

        $res = [];
        foreach( $this->set as $card ) {
            $val = self::SUIT;
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
            $val = self::NUMBER;
            $res[] = $card->$val;
            if( !in_array($card->$val, Combination::$order) ){
                throw new Exception('Unknown card number');
            }
        }

        return $res;
    }
}