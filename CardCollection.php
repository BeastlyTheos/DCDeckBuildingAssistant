<?php
class CardCollection implements IteratorAggregate
{
public static $numCardsDealt;
public $cards;

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

public function moveCardTo( $id, $recipient)
	{
	$recipient->recieveCard( $this->cards[$id]);
	$this->destroyCard($id);
	}//end move card

private function recieveCard($card)
	{$this->cards[ $card["id"]] = $card;}
}// end class CardCollection
?>
