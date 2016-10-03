<?php

namespace App\Http\Controllers;

use App\Game;

class PokerController extends Controller
{
    public function shuffle()
    {
        $game = new Game();
        $new_game = true;
        $game->start($new_game);
        echo ( json_encode( $game->result_logger ) );
        return view('poker');
    }

    public function deal()
    {
        $game = new Game();
        $game->start();
        print_r( $game->result_logger );
        return view('poker');
    }
}
