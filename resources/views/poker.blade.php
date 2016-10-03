<table border="1">
    <tr>
        <th colspan="2"><marquee>ComparaOnline Pokeroom 2016</marquee></th>
    </tr>
    <tr>
        <td>Set1</td>
        <td>Set2</td>
    </tr>
    <tr>
        <td>Combination1</td>
        <td>Combination2</td>
    </tr>
    <tr>
        <td>Winner1</td>
        <td>Winner1</td>
    </tr>
    <tr>
        <td colspan="2">Game over / Game espared</td>
    </tr>
    <tr>
        <td colspan="2">Result of the game</td>
    </tr>
    <tr>
        <td>
            <form method="post" action="{{ action('PokerController@deal') }}">
                <input type="submit" name="deal" value="Deal">
            </form>
        </td>
        <td>
            <form method="post" action="{{ action('PokerController@shuffle') }}">
                <input type="submit" name="shuffle" value="Shuffle">
            </form>
        </td>
    </tr>
</table>
