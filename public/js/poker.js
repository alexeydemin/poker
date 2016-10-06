var Set = React.createClass({
    render: function() {
        return <span className={this.props.data.color == 'red' ? 'card-set red' : 'card-set'} dangerouslySetInnerHTML={{__html: ''+ this.props.data.unicode + ''}} />
    }
});

var Table = React.createClass({
    getInitialState: function () {
        return {deck: '', combination1: '', combination2: '', player1set:[],  player2set:[], info: ''};
    },
    sendRequest: function ( action ) {


        $.post('/' + action, function (r) {
            var result = JSON.parse(r);

            function test_func(winner){
                if( winner == '0'){
                    return 'Tie';
                } else{
                    return 'Player ' + result.winner + ' wins';
                }
            }
            if( result.error_msg ){
                this.setState({ info: result.error_msg});
            } else {

                this.setState({
                    deck: result.deck,
                    player1set: result.player1set,
                    player2set: result.player2set,
                    combination1: result.combination1,
                    combination2: result.combination2,
                    winner: test_func(result.winner),
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
                        <td className="tg-yw4l">player1Score</td>
                        <td className="tg-yw4l">player2Score</td>
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