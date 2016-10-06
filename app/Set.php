<?php

namespace App;

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
        $this->decorateUnicode();
        if( $this->set  != array_unique($this->set, SORT_REGULAR) ){
            throw new Exception('Deck inconsistency detected!');
        }
    }


    /**
     * Adds corresponding unicode symbol for each card in a set
     */
    private function decorateUnicode()
    {
        $suite_caption = self::SUIT;
        $number_caption = self::NUMBER;
        foreach($this->set as $single_card){
            $single_card->unicode = '&#x1f0' . Repository::$s_parts[ $single_card->$suite_caption ] . Repository::$n_parts[ $single_card->$number_caption ] . ';' ;
            $single_card->color = in_array($single_card->$suite_caption, ['hearts', 'diamonds']) ? 'red' : 'black';
        }
    }


    /**
     * Returns array of suits in a set
     * e.g ['diamonds', 'clubs', 'clubs', 'hearts', 'spades']
     * @return array
     * @throws Exception
     */
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

    /**
     * Rerurns array of card numbers
     * e.g. ['K', 'Q', '7', '9', '7']
     * @return array
     * @throws Exception
     */
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