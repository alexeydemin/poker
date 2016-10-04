<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Requester;

class Game extends Model
{
    const TIE = 'Tie';
    const PLAYER1 = 'Player 1 wins';
    const PLAYER2 = 'Player 2 wins';

    public $player1_score = 0;
    public $player2_score = 0;
    public $result_logger;

    public function start( $new_game = false )
    {
        $r = new Requester;
        $deck = $r->getDeck( $new_game );
        $this->result_logger['deck'] = $deck;
        $json1 = $r->getCardSet($deck);
        if ($json1) {
            $player1Set = new Set($json1);
            $this->result_logger['player1set'] = (array) $player1Set->set;

            $json2 = $r->getCardSet($deck);
            $player2Set = new Set($json2);
            $this->result_logger['player2set'] = (array) $player2Set->set;

            $combination1 = new Combination($player1Set);
            $combination2 = new Combination($player2Set);

            $this->result_logger['combination1'] = $combination1->description;
            $this->result_logger['combination2'] = $combination2->description;

            $winner = $this->compareCombinations($combination1, $combination2);
            $this->result_logger['winner'] = $winner;

            if( $winner == self::PLAYER1 ){
                $this->player1_score++;
            }
            if( $winner == self::PLAYER2 ){
                $this->player2_score++;
            }
            //TODO: Remove output
            //echo "\nPLAYER ONE SCORE=" . $this->player1_score ."\n";
            //echo "\nPLAYER TWO SCORE=" . $this->player2_score ."\n";
        }
    }

    public static function compareCombinations($comb1, $comb2)
    {
        // TODO: Get highest from the combination
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