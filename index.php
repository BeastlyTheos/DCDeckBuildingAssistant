<?php $debug = true;
require_once 'C:/xampp/vendor/autoload.php';
  $loader = new Twig_Loader_Filesystem('templates');
 $twig = new Twig_Environment($loader, array("debug"=>$debug));

require "sql.php";
require "CardCollection.php";

session_start();

if ( ! isset($_SESSION["hand"]) )
	$_SESSION["hand"] = new CardCollection();
$hand = $_SESSION["hand"];

if ( ! isset($_SESSION["deck"]) )
	$_SESSION["deck"] = new CardCollection();
$deck = $_SESSION["deck"];

if ( ! isset($_SESSION["discard"]) )
	$_SESSION["discard"] = new CardCollection();
$discard = $_SESSION["discard"];

if ( ! isset($_SESSION["lineup"]) )
	$_SESSION["lineup"] = new CardCollection();
$lineup = $_SESSION["lineup"];

if ( ! isset($_SESSION["cards"]) )
	{
	$_SESSION["cards"] = array();
	$res = $sql->query("select * from cards");
	while ( $row = $res->fetch_assoc())
		$_SESSION["cards"][$row["id"]] = $row;
	}
$cards = $_SESSION["cards"];

if ( ! isset($_SESSION["numCardsDealt"]) )
	$_SESSION["numCardsDealt"] = 0;
CardCollection::$numCardsDealt = $_SESSION["numCardsDealt"];

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
					$lineup->createCard( $cards[$key]);

			break;
			}//end cards form
		}//end switch on formName
	}// end if formName is set

$_SESSION["lineup"] = $lineup;
$_SESSION["cards"] = $cards;
$_SESSION["numCardsDealt"] = CardCollection::$numCardsDealt;
$_SESSION["discard"] = $discard;
$_SESSION["deck"] = $deck;
$_SESSION["hand"] = $hand;

$twig->display("index.html", array("cards"=>$cards, "lineup"=>$lineup, "discard"=>$discard, "deck"=>$deck, "hand"=>$hand));
?>
