// Correct :)
var Set = React.createClass({
    render: function() {
        //<div>{'First ' + String.fromCharCode(183) + ' Second'}</div>
        return <span>{'' + this.props.data.unicode + ''}</span>;
    }
});

var Table = React.createClass({
    getInitialState: function () {
        return {deck: '', combination1: '', player1set:[],  player2set:[]};
    },
    sendRequest: function ( action ) {
        var path = '/' + action;
        $.post(path, function (r) {
            console.log('PATH='+path);
            console.log('RES1='+r);
            var result = JSON.parse(r);
            this.setState({
                deck: result.deck,
                player1set: result.player1set,
                player2set: result.player2set,
                combination1: result.combination1,
                combination2: result.combination2,
                winner: result.winner
            });
            console.log('COMB1='+this.state.combination1);
            console.log('PLAYER1SET='+this.state.player1set);
            console.log('PLAYER2SET='+this.state.player2set);
        }.bind(this));
    },

    deal: function(){
        this.sendRequest( 'deal' );
    },

    shuffle: function(){
        this.sendRequest( 'shuffle' );
    },
    render: function() {
        return(
            <div>
                <table className="tg">
                    <tbody>
                    <tr>
                        <th className="tg-yw4l" colSpan="2">{'\x1f0c1 \u1f0c1 \uf0c1 \u2665  ComparaOnline \u00b7 Pokeroom 2016 \u2666 \u2663' + String.fromCharCode(parseInt('1F0D1', 32)) }</th>
                    </tr>
                    <tr>
                        <td className="tg-yw4l">

                                {this.state.player1set.map((card) => (
                                    <Set key={card.unicode} data={card} />
                                ))}

                        </td>
                        <td className="tg-yw4l">
                            {this.state.player2set.map((card) => (
                                <Set key={card.unicode} data={card} />
                            ))}
                        </td>
                    </tr>
                    <tr>
                        <td className="tg-yw4l">{this.state.combination1}</td>
                        <td className="tg-yw4l">{this.state.combination2}</td>
                    </tr>
                    <tr>
                        <td className="tg-yw4l" colSpan="2">{this.state.winner}</td>
                    </tr>
                    <tr>
                        <td className="tg-yw4l" colSpan="2">Game over / Game expired</td>
                    </tr>
                    <tr>
                        <td className="tg-yw4l" colSpan="2">Result of the game</td>
                    </tr>
                    <tr>
                        <td className="tg-yw4l"><input type="button" onClick={this.deal} value="Deal"/></td>
                        <td className="tg-yw4l"><input type="button" onClick={this.shuffle} value="Shuffle"/></td>
                    </tr>
                    </tbody>
                </table>
        </div>
        );
    }
});

ReactDOM.render(
<Table />,
    document.getElementById('content')
);