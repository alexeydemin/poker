var Set = React.createClass({
    render: function() {
        return <span className={this.props.data.color == 'red' ? 'card-set red' : 'card-set'} dangerouslySetInnerHTML={{__html: ''+ this.props.data.unicode + ''}} />
    }
});

var Table = React.createClass({
    getInitialState: function () {
        return {deck: '', combination1: '', combination2: '', player1set:[],  player2set:[], info: '', player1Score: 0, player2Score: 0};
    },
    sendRequest: function ( action ) {

        $.post('/' + action, function (r) {
            var result = JSON.parse(r);
            if( result.error_msg ){
                this.setState({ info: result.error_msg});
            } else {
                if( result.winner == 1)
                    this.state.player1Score++;
                if( result.winner == 2)
                    this.state.player2Score++;
                this.setState({
                    deck: result.deck,
                    player1set: result.player1set,
                    player2set: result.player2set,
                    combination1: result.combination1,
                    combination2: result.combination2,
                    winner: result.winner ? 'Player ' + result.winner + ' wins' : 'Tie',
                    info: ''
                });
            }
        }.bind(this));
    },

    deal: function(){
        this.sendRequest( 'deal' );
    },

    shuffle: function(){
        this.sendRequest( 'shuffle' );
        this.state.player1Score=0;
        this.state.player2Score=0;
    },
    render: function() {
        return(
            <div>
                <table className="tg">
                    <tbody>
                    <tr>
                        <th className="tg-yw4l" colSpan="2">{'\u2660 \u2665  ComparaOnline \u00b7 Pokeroom 2016 \u2666 \u2663'}</th>
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
                        <td className="tg-yw4l" colSpan="2">{this.state.info}</td>
                    </tr>
                    <tr>
                        <td className="tg-yw4l">{this.state.player1Score}</td>
                        <td className="tg-yw4l">{this.state.player2Score}</td>
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