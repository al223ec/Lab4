<?php


require_once("src/model/user.php");
require_once("common/htmlview.php");
require_once("core/router.php"); 

session_start();

$router = new Router();  
$view = new  HTMLView();
$view->echoHTML($router->dispatch());

/** Angående att spara object i session - http://stackoverflow.com/questions/132194/php-storing-objects-inside-the-session

* Whether you save objects in $_SESSION, or reconstruct them whole cloth based on data stashed in hidden form 
* fields, or re-query them from the DB each time, you are using state. HTTP is stateless (more or less; but see GET vs. PUT)
* but almost everything anybody cares to do with a web app requires state to be maintained somewhere. 
*Acting as if pushing the state into nooks and crannies amounts to some kind of theoretical win is just wrong.
* State is state. If you use state, you lose the various technical advantages gained by being stateless. This is 
*not something to lose sleep over unless you know in advance that you ought to be losing sleep over it.
*
*Is the OP building a distributed and load-balanced e-commerce system? My guess is no; and I will further posit 
* that serializing his $User class, or whatever, will not cripple his server beyond repair. My advice: use techniques
*  that are sensible to your application. Objects in $_SESSION are fine, subject to common sense precautions. If your app 
* suddenly turns into something rivaling Amazon in traffic served, you will need to re-adapt. That's life. 
*/