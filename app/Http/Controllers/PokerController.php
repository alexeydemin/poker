<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Game;

class PokerController extends Controller
{
    public function index()
    {
        echo '[] [] [] [] []';
        $game = new Game();
        echo '<pre>';
        $game->start();
        echo '</pre>';

    }

    public function shuffle()
    {
        echo 'shuffled';
    }

    public function deal()
    {
        echo 'dealt';
    }
}
