<?php

namespace App\Http\Controllers;

use App\Game;

class PokerController extends Controller
{
    public $game;

    public function __construct()
    {
        $this->game = new Game();
    }

    public function shuffle()
    {
        $new_game = true;
        $this->game->start($new_game);
        echo ( json_encode( $this->game->result_logger ) );
    }

    public function deal()
    {
        $this->game->start();
        echo ( json_encode( $this->game->result_logger ) );
    }
}
