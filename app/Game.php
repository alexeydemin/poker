<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Requester;

class Game extends Model
{
    const TIE = 'Tie';
    const PLAYER1 = 'Player 1 wins';
    const PLAYER2 = 'Player 2 wins';

    public function start()
    {

        $r = new Requester;
        $deck = $r->getDeck();
        print_r( $deck );

        $json1 = $r->getCardSet($deck);
        $player1Set = new Set($json1);

        $json2 = $r->getCardSet($deck);
        $player2Set = new Set($json2);

        $combination1 = new Combination($player1Set);
        $combination2 = new Combination($player2Set);

        echo '<br>';
        echo $combination1->description . '<br>';
        echo $combination2->description. '<br>';

        echo self::compareCombinations($combination1, $combination2);
    }

    public static function compareCombinations($comb1, $comb2)
    {
        if( $comb1->rank == $comb2->rank ){
            if( $comb1->highest > $comb2->highest )
                return self::PLAYER1;
            if( $comb1->highest < $comb2->highest )
                return self::PLAYER2;
            if( $comb1->highest == $comb2->highest )
                return self::TIE;
        }
        return $comb1->rank > $comb2->rank ? self::PLAYER1 : self::PLAYER2;
    }


}