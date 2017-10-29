<?php $debug = true;
require_once 'C:/xampp/vendor/autoload.php';
  $loader = new Twig_Loader_Filesystem('templates');
 $twig = new Twig_Environment($loader, array("debug"=>$debug));

require "sql.php";

session_start();

if ( ! isset($_SESSION["cards"]) )
	{
	$_SESSION["cards"] = array();
	$res = $sql->query("select * from cards");
	while ( $row = $res->fetch_assoc())
		$_SESSION["cards"][$row["id"]] = $row;
	}
$cards = $_SESSION["cards"];

$twig->display("index.html", array("cards"=>$cards));
?>
