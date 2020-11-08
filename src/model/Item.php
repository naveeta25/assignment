<?php


/**
 * Item
 *
 * 
 */

class Item
{
	protected $name;
	protected $amount;
	protected $unit;
	protected $expiration;

	/**
	 * Set name of the the item
	 *
	 * @param string $name
	 */
	public function setName($name = "")
	{
		if (empty($name)) {
			throw new \Exception("Item name can't be empty");
		} else {
			$this->name = $name;
		}
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set amount of items
	 *
	 * @param string/int $amount
	 */
	public function setAmount($amount = 0)
	{
		$this->amount = floatval($amount);
	}

	/**
	 * Increase the amount of the item
	 *
	 * @param string/int $amount
	 */
	public function increaseAmount($amount = 0)
	{
		$this->amount += floatval($amount);
	}

	/**
	 * @return float
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Set the unit of the item
	 *
	 * @param string $unit
	 */
	public function setUnit($unit = "")
	{
		if (empty($unit) || !in_array($unit, array('of','grams','ml','slices'))) {
			throw new \Exception("Item unit can be only of, grams, ml or slices");
		} else {
			$this->unit = $unit;
		}
	}

	/**
	 * @return string
	 */
	public function getUnit()
	{
		return $this->unit;
	}

	/**
	 * Set the expiration of the item
	 *
	 * @param string $expiration in the format dd/mm/yyyy
	 */
	public function setExpiration($expiration = "")
	{
		//this should be set in the php.ini
		date_default_timezone_set('Australia/Sydney'); 

		//we replace / for - so strtotime can understand d/m/y
		if (empty($expiration)) {
			//if expiration is empty we assume that the item is not going to expire any time soon
			$this->expiration = strtotime('next year');
		} else {
			$expiration_formatted = str_replace("/", "-", $expiration);
			$expiration_time = strtotime($expiration_formatted);
			if ($expiration_time === false) {
				//there was a problem parsing this date
				throw new \Exception("Item expiration date should be in the following format dd/mm/yyyy");
			} else {
				$this->expiration = $expiration_time;
			}
		}
	}

	/**
	 * @return boolean
	 */
	public function isExpired()
	{
		//this should be set in the php.ini
		date_default_timezone_set('Australia/Sydney'); 

		return $this->expiration < time();
	}

	/**
	 * @return int
	 */
	public function getExpiration()
	{
		return $this->expiration;
	}
}