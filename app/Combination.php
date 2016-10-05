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
    public $rank;
    public $description;
    public $set;
    public $highest;
    public $second = false;

    public function __construct(Set $set)
    {
        $this->set = $set;
        $this->Ace = count(self::$order)-1;
        $this->setInternalArray();
        echo $this->getCombination() . "\n";
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
        //array_multisort(array_values($arr), SORT_DESC, array_keys($arr), SORT_DESC, $arr);
        // TODO: Provide with correct sort!
        arsort( $arr );
        return $arr;
    }

    public function checkStraight()
    {
        $range = range( min($this->internal_set), min($this->internal_set) + count($this->internal_set)-1 );

        return $this->internal_set == $range ? max($this->internal_set) : false;
    }

    public function getCombination()
    {
        $numberList = $this->aggregate($this->internal_set);
        reset($numberList);
        $topSubset = key($numberList);  //most common card
        next($numberList);
        $secondSubset = key($numberList); // second most common
        echo 'top =' . $topSubset;
        echo 'second =' . $secondSubset;
        die;
        $suits = $this->set->getSuits();
        $suitList = $this->aggregate( $suits );
        $is_flush = reset($suitList) == 5;

        if( $this->checkStraight() && $is_flush && $this->internal_set[4] = $this->Ace ) {
            $cmb = self::_10_ROYAL_FLUSH;
            $this->highest = $this->Ace;
        }
        elseif( $this->checkStraight() && $is_flush) {
            $cmb = self::_9_STRAIGHT_FLUSH;
            $this->highest = max($this->internal_set);
        }
        elseif( $numberList[$topSubset] == 4 ) {
            $cmb = self::_8_FOUR_OF_A_KIND;
            $this->highest = $topSubset;
        }
        elseif( $numberList[$topSubset] == 3 && $numberList[$secondSubset] == 2 ) {
            $cmb = self::_7_FULL_HOUSE;
            $this->highest = $topSubset;
            $this->second = $secondSubset;
        }
        elseif( $is_flush ) {
            $cmb = self::_6_FLUSH;
            $this->highest = max($this->internal_set);
        }
        elseif($this->checkStraight() ) {
            $cmb = self::_5_STRAIGHT;
            $this->highest = max($this->internal_set);
        }
        elseif( $numberList[$topSubset] == 3 ) {
            $cmb = self::_4_THREE_OF_A_KIND;
            $this->highest = $topSubset;
        }
        elseif( $numberList[$topSubset] == 2 && $numberList[$secondSubset] == 2 ) {
            $cmb = self::_3_TWO_PAIRS;
            $this->highest = max($topSubset, $secondSubset);
            $this->second = $secondSubset;
        }
        elseif( $numberList[$topSubset] == 2 ) {
            $cmb = self::_2_ONE_PAIR;
            $this->highest = $topSubset;
        } else{
            $cmb = self::_1_HIGH_CARD;
            $this->highest = max($this->internal_set);
            $this->second = $secondSubset;
        }

        $this->rank = explode('.', $cmb)[0];
        $this->description = explode('.', $cmb)[1]
                           . ' [' . self::$order[$this->highest] . ']'
                           . ( $this->second !== false ? ( '(' . self::$order[$this->second] . ')' ) : '' );
    }
}