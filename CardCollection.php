<?php
class CardCollection implements IteratorAggregate
{
public static $numCardsDealt;
private $cards;

public function __construct()
	{$this->cards = array();}

public function getIterator()
	{return new ArrayIterator( $this->cards);}

public function createCard( $card)
	{
	$card["id"] = self::$numCardsDealt++;
	$this->cards[$card["id"]] = $card;
	}//end create card

public function destroyCard($id)
	{unset($this->cards[$id]);}
}// end class CardCollection
?>
