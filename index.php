<?php $debug = true;
require_once 'C:/xampp/vendor/autoload.php';
  $loader = new Twig_Loader_Filesystem('templates');
 $twig = new Twig_Environment($loader, array("debug"=>$debug));

require "sql.php";
require "CardCollection.php";

session_start();

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
		case "lineup":
			{
			if ( "destroy" == $_POST["submit"] )
				foreach ( array_keys($_POST) as $key )
					if ( "on" == $_POST[$key] )
						$lineup->destroyCard($key);

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

$twig->display("index.html", array("cards"=>$cards, "lineup"=>$lineup));
?>
