# Technical task for a small Chilean company
Eventually they didn't hire me (because of my bad Spanish I guess).

The app created with Laravel 5.4 and a bit of React deployed here 
http://poker.alexeydemin.in
 

We want to see how do you handle a small web development project, you can choose a 
programming language and library/frameworks (except for evaluating the poker hands, that you 
must implement it yourself) if needed, but ​ the result must be a working website. 
We’d like you to code a small web app that takes two hands of cards and evaluates which one 
wins using poker rules. 
The app must satisfy the following requirements:

1. Cards should come from our dealer service. Check the documentation below. 

2. Once you get the two hands you need to display them to the client (we won’t evaluate 
the design of the interface, only how it’s coded). You will also display the result (which of 
the two hands won), and the name of the combination. 

3. You should provide two actions, either to deal the next hands, up to when there aren’t 
any more cards on the deck, or to shuffle the deck and start over. 
 
Below you have the documentation for the dealer service and the rules of how to evaluate which 
hand wins. You need to provide us with a github (bitbucket or gitlab are also fine) repository 
with clear instructions on how to set up an environment to test it​ . If you want to include any 
kind of documentation or notes, please add them in a 
doc/  directory.  
 
Feel free to take any decisions on things not included here (and explain them in the ​ doc/ 
directory if you think it’s necessary).  
Dealer service 
You can reach the dealer service on this url: ​ http://dealer.internal.comparaonline.com:8080/​ . 
 
The service can’t be changed​ , so you might need to find workarounds for any issues you 
might face. 
 
The service is designed to fail often​  (throw a 500 HTTP error), so you need to account for 
that, the user experience should never be affected. 
 
Here you can find example calls to both endpoints: 
https://gist.github.com/plataforma­co/57691938b63c3c29418a9bf5dca7b896 

######POST /deck 
Calling this method shuffles a new deck (52 cards). Decks expired after 5 minutes without being 
used, so you should handle that possibility. It returns a 36 character token, plain text. 
 
######GET /deck/{TOKEN}/deal/{AMOUNT} 
This deals an {AMOUNT} of cards for the specified deck {TOKEN}. The result is a JSON array 
containing objects with ​ “number” and ​ “suit” properties. “number” is a string that can be one of: A, 
2, 3, 4, 5, 6, 7, 8, 9, 10, J, Q, K. “suit” is also a string and can be either hearts, diamonds, clubs 
or spades. 
If there aren’t enough cards to deal the amount requested, it will throw a 405 HTTP error. 
If the deck isn’t found (doesn’t exist, or expired) it will throw a 404 HTTP error. 