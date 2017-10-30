<?php
class CardCollection
{
public static $numCardsDealt;
private $cards;

public function __construct()
	{$this->cards = array();}

public function createCard( $card)
	{
	$card["id"] = self::$numCardsDealt++;
	$this->cards[$card["id"]] = $card;
	}//end create card
}// end class CardCollection
?>
