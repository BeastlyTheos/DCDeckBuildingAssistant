<?php
class CardCollection implements IteratorAggregate
{
public static $masterList;
public static $numCardsDealt;
public $cards;

public function __construct()
	{$this->cards = array();}

public function getIterator()
	{return new ArrayIterator( $this->cards);}

public function createCardByID( $id)
	{
	if ( isset(self::$masterList[$id]) )
		{
		$card = self::$masterList[$id];
		$card["id"] = self::$numCardsDealt++;
		$this->cards[$card["id"]] = $card;
		}
	}//end create card

public function destroyCard($id)
	{unset($this->cards[$id]);}

public function moveAllCardsTo( $recipient)
	{
	foreach ( $this->cards as $card )
		$this->moveCardTo( $card["id"], $recipient);
	}//end move all cards

public function moveCardTo( $id, $recipient)
	{
	if ( isset($this->cards[$id]) )
		{
		$recipient->recieveCard( $this->cards[$id]);
		$this->destroyCard($id);
		}
	}//end move card

private function recieveCard($card)
	{
	if ( $card["id"] )
		$this->cards[ $card["id"]] = $card;
	}
}// end class CardCollection
?>
