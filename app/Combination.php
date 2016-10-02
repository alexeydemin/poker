<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Combination extends Model
{
    public static $order = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
    public static $suits = ['clubs', 'diamonds', 'hearts', 'spades' ];

    const _1_HIGH_CARD       = '1. High Card: Highest value card.';
    const _2_ONE_PAIR        = '2. One Pair: Two cards of the same value.';
    const _3_TWO_PAIRS       = '3. Two Pairs: Two different pairs.';
    const _4_THREE_OF_A_KIND = '4. Three of a Kind: Three cards of the same value.';
    const _5_STRAIGHT        = '5. Straight: All cards are consecutive values.';
    const _6_FLUSH           = '6. Flush: All cards of the same suit.';
    const _7_FULL_HOUSE      = '7. Full House: Three of a kind and a pair.';
    const _8_FOUR_OF_A_KIND  = '8. Four of a Kind: Four cards of the same value.';
    const _9_STRAIGHT_FLUSH  = '9. Straight Flush: All cards are consecutive values of same suit.';
    const _10_ROYAL_FLUSH    = '10. Royal Flush: Ten, Jack, Queen, King, Ace of same suit.';

    //Array of internal numbers
    private $internal_set;
    private $Ace;

    public function __construct(Set $set)
    {
        $this->set = $set;
        $this->Ace = count(self::$order)-1;
        $this->setInternalArray();
        echo $this->getCombination() . "\n";
        //$this->
    }

    public function setInternalArray()
    {
        $conv = array_flip(self::$order);
        $res = [];
        $arr = $this->set->getNumbers();
        foreach( $arr as  $s){
            $res[] = $conv[$s];
        }
        sort($res);
        $this->internal_set = $res;
    }


    public static function aggregate($e)
    {
        $arr =  array_count_values( $e );
        rsort( $arr );
        return $arr;
    }

    public function checkStraight()
    {
        $range = range( min($this->internal_set), min($this->internal_set) + count($this->internal_set)-1 );

        return $this->internal_set == $range ? max($this->internal_set) : false;
    }

    public function getCombination()
    {
        //$numbers = $this->set->getNumbers();
        $numberList = $this->aggregate($this->internal_set);
        $suits = $this->set->getSuits();
        $suitList = $this->aggregate( $suits );

        if( $this->checkStraight() && $suitList[0] == 5 && $this->internal_set[4] = $this->Ace )
            return self::_10_ROYAL_FLUSH;
        if( $this->checkStraight() && $suitList[0] == 5 )
            return self::_9_STRAIGHT_FLUSH;
        if( $numberList[0] == 4 )
            return self::_8_FOUR_OF_A_KIND;
        if( $numberList[0] == 3 && $numberList[1] == 2 )
            return self::_7_FULL_HOUSE;
        if( $suitList[0] == 5)
            return self::_6_FLUSH;
        if($this->checkStraight() )
            return self::_5_STRAIGHT;
        if( $numberList[0] == 3 )
            return self::_4_THREE_OF_A_KIND;
        if( $numberList[0] == 2 && $numberList[1] == 2 )
            return self::_3_TWO_PAIRS;
        if( $numberList[0] == 2 )
            return self::_2_ONE_PAIR;
        return self::_1_HIGH_CARD;
    }
}