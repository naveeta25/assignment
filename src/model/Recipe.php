<?php


/**
 * Recipe
 
 */

class Recipe
{
	protected $name;
	protected $ingredients = array();

	/**
	 * Set name of the the recipe
	 *
	 * @param string $name
	 */
	public function setName($name = "")
	{
		if (empty($name)) {
			throw new \Exception("Name can't be empty");
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
	 * Add an ingredient to the recipe
	 *
	 * @param string $name
	 * @param string $amount
	 * @param string $unit
	 */
	public function addIngredient($name = "", $amount = 0, $unit = "")
	{
		$item = new Item();
		$item->setName($name);
		$item->setAmount($amount);
		$item->setUnit($unit);
		$this->ingredients[] = $item;
	}

	/**
	 * @return array
	 */
	public function getIngredients()
	{
		return $this->ingredients;
	}
}