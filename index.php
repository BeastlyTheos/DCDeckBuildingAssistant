<?php $debug = true;
require_once 'C:/xampp/vendor/autoload.php';
  $loader = new Twig_Loader_Filesystem('templates');
 $twig = new Twig_Environment($loader, array("debug"=>$debug));

require "sql.php";
require "CardCollection.php";

session_start();

if ( ! isset($_SESSION["cards"]) )
	{
	$_SESSION["cards"] = array();
	$res = $sql->query("select * from cards");
	while ( $row = $res->fetch_assoc())
		$_SESSION["cards"][$row["id"]] = $row;
	}
CardCollection::$masterList = $_SESSION["cards"];

if ( ! isset($_SESSION["numCardsDealt"]) )
	$_SESSION["numCardsDealt"] = 0;
CardCollection::$numCardsDealt = $_SESSION["numCardsDealt"];

if ( ! isset($_SESSION["hand"]) )
	$_SESSION["hand"] = new CardCollection();
$hand = $_SESSION["hand"];

if ( ! isset($_SESSION["ongoings"]) )
	$_SESSION["ongoings"] = new CardCollection();
$ongoings = $_SESSION["ongoings"];

if ( ! isset($_SESSION["deck"]) )
	{
	$_SESSION["deck"] = new CardCollection();
	for ( $i = 0 ; $i < 7 ; $i++ )
		$_SESSION["deck"]->createCardByID( 323); //initialise 7 punches
	for ( $i = 0 ; $i < 3 ; $i++ )
	$_SESSION["deck"]->createCardByID( 429); //initialise 3 vulnerabilities
	}
$deck = $_SESSION["deck"];

if ( ! isset($_SESSION["discard"]) )
	$_SESSION["discard"] = new CardCollection();
$discard = $_SESSION["discard"];

if ( ! isset($_SESSION["lineup"]) )
	$_SESSION["lineup"] = new CardCollection();
$lineup = $_SESSION["lineup"];

if ( isset($_POST["formName"]) )
	{
	switch ( $_POST["formName"] )
		{
		case "hand":
			{
			if ( "end_turn" == $_POST["submit"] )
				$hand->moveAllCardsTo ( $discard);
			elseif ( "discard" == $_POST["submit"] )
				{
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$hand->moveCardTo ( $key, $discard);
				}//end discarding from hand
			elseif ( "pin" == $_POST["submit"] )
				{
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$hand->moveCardTo ( $key, $ongoings);
				}//end pinning
			elseif ( "unpin" == $_POST["submit"] )
				{
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$ongoings->moveCardTo ( $key, $hand);
				}//end unpinning

			break;
			}//end hand form
		case "deck":
			{
			if ( "deal" == $_POST["submit"] )
				{
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$deck->moveCardTo ( $key, $hand);
				}//end moving to deck

			break;
			}//end hand form
		case "discard":
			{
			if ( "shuffle" == $_POST["submit"] )
				$discard->moveAllCardsTo( $deck);
			elseif ( "undeal" == $_POST["submit"] )
				{
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$discard->moveCardTo( $key, $deck);
				}//end undealing
			elseif ( "destroy" == $_POST["submit"] )
				{
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$discard->destroyCard( $key);
				}//end destroying

			break;
			}//end discard form
		case "lineup":
			{
			if ( "aquire" == $_POST["submit"] )
				{
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$lineup->moveCardTo( $key, $discard);
				}
			elseif ( "destroy" == $_POST["submit"] )
				{
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$lineup->destroyCard($key);
				}

			break;
			}//end lineup form
		case "card_list":
			{
			foreach ( array_keys($_POST) as $key )
				if ( "on" == $_POST[$key] )
					$lineup->createCardByID( $key);

			break;
			}//end cards form
		}//end switch on formName
	}// end if formName is set

$_SESSION["lineup"] = $lineup;
$_SESSION["cards"] = CardCollection::$masterList;
$_SESSION["numCardsDealt"] = CardCollection::$numCardsDealt;
$_SESSION["discard"] = $discard;
$_SESSION["deck"] = $deck;
$_SESSION["hand"] = $hand;
$_SESSION["ongoings"] = $ongoings;

$twig->display("index.html", array("cards"=>CardCollection::$masterList, "lineup"=>$lineup, "discard"=>$discard, "deck"=>$deck, "hand"=>$hand, "ongoings"=>$ongoings));
?>
