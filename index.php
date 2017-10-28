<?php $debug = true;
require_once 'C:/xampp/vendor/autoload.php';
  $loader = new Twig_Loader_Filesystem('templates');
 $twig = new Twig_Environment($loader, array("debug"=>$debug));

require "sql.php";

session_start();

$twig->display("index.html", array("cards"=>$cards));
?>
