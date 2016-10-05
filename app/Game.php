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
    public $result_logger = ['error_msg' => 0];

    public function start( $new_game = false )
    {
        $r = new Requester;
        $deck = $r->getDeck( $new_game );
        $this->result_logger['deck'] = $deck;


          //$data = $r->getCardSet($deck);
        $data = [ 'body' => '[{"number":"K","suit":"hearts"},
                              {"number":"K","suit":"clubs"},
                              {"number":"2","suit":"clubs"},
                              {"number":"2","suit":"hearts"},
                              {"number":"J","suit":"diamonds"}]',
                'error_msg' => 0 ];
        if ($data['error_msg']) {
            $this->result_logger['error_msg'] = $data['error_msg'];
        } else {
            $player1Set = new Set($data['body']);
            $this->result_logger['player1set'] = (array) $player1Set->set;

            //$json2 = $r->getCardSet($deck)['body'];
            $json2 = '[{"number":"K","suit":"hearts"}
                      ,{"number":"K","suit":"clubs"}
                      ,{"number":"7","suit":"clubs"}
                      ,{"number":"7","suit":"spades"}
                      ,{"number":"J","suit":"diamonds"}]';
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
        }
    }

    public static function compareCombinations($comb1, $comb2)
    {
        if( $comb1->rank == $comb2->rank ){
            if( $comb1->highest > $comb2->highest )
                return self::PLAYER1;
            if( $comb1->highest < $comb2->highest )
                return self::PLAYER2;
            if( $comb1->highest == $comb2->highest ){
                if( $comb1->second !== false &&  $comb1->second !== false) { //3-2 or 2-2 or 1-1
                    if( $comb1->second > $comb2->second )
                        return self::PLAYER1;
                    if( $comb1->second < $comb2->second )
                        return self::PLAYER2;
                    else
                        return self::TIE;
                } else{
                    return self::TIE;
                }
            }
        }
        return $comb1->rank > $comb2->rank ? self::PLAYER1 : self::PLAYER2;
    }


}