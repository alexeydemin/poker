<?php

namespace App;

class Game
{
    const TIE = '0';
    const PLAYER1 = '1';
    const PLAYER2 = '2';

    public $result_logger = ['error_msg' => 0];


    /**
     * @param bool $new_game - true if new game (shuffle action)
     * Game logic
     */
    public function start($new_game = false )
    {
        $r = new Requester;
        $deck = $r->getDeck( $new_game );
        $this->result_logger['deck'] = $deck;

        $data = $r->getCardSet($deck);
        /*$data = [ 'body' => '[{"number":"9","suit":"hearts"},
                              {"number":"9","suit":"clubs"},
                              {"number":"8","suit":"diamonds"},
                              {"number":"8","suit":"spades"},
                              {"number":"6","suit":"hearts"}]',
                'error_msg' => 0 ];*/
        if ($data['error_msg']) {
            $this->result_logger['error_msg'] = $data['error_msg'];
        } else {
            $player1Set = new Set($data['body']);
            $this->result_logger['player1set'] = (array) $player1Set->set;

            $json2 = $r->getCardSet($deck)['body'];
            /*$json2 = '[{"number":"K","suit":"hearts"}
                      ,{"number":"K","suit":"clubs"}
                      ,{"number":"7","suit":"clubs"}
                      ,{"number":"7","suit":"spades"}
                      ,{"number":"J","suit":"diamonds"}]';*/
            $player2Set = new Set($json2);
            $this->result_logger['player2set'] = (array) $player2Set->set;

            $combination1 = new Combination($player1Set);
            $combination2 = new Combination($player2Set);

            $this->result_logger['combination1'] = $combination1->description;
            $this->result_logger['combination2'] = $combination2->description;

            $winner = self::compareCombinations($combination1, $combination2);
            $this->result_logger['winner'] = $winner;
        }
    }

    /**
     * @param $comb1 Combination of first player
     * @param $comb2 Combination of second player player
     * @return string Winner const
     */
    private static function compareCombinations($comb1, $comb2)
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