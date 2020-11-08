
<?php 

require_once('src/util/GetInputFile.php') ;
require_once('src/model/Recipe.php') ;


    class Finder {

        protected $fridgData;

        public $fridgeFile;
        public $recipe;

        public function __construct($fridgeFile, $recipe) {
            $this->fridgeFile = $fridgeFile;
            $this->recipe = $recipe;
            $this->getTheDish();
        }
        public function getTheDish(){
            try{

                //load the fridge
                $getData = new GetInputFile();
                $this->fridgetData = $getData->loadFile($this->fridgeFile);
               
                //get the possible recipes
                $recipeData = $getData->getJsonData($this->recipe);
                
            } catch(\Exception $e) {
                print_r('<error>'.$e->getMessage().'</error>');
                //big error
                return;
            }
          
            if (count($this->fridgetData) == 0) {
                print_r('Your fridge is empty! Order Takeout');
                //nothing else to do
                return;
            }
            $cookbook = $this->setUpCookbook($recipeData);
            
            if (count($cookbook) == 0) {
                $output->writeln('No recipes, no suggestions! Order Takeout');
                //nothing else to do
                return;
            }

            $suggestions = array();
            foreach ($cookbook as $recipe) {
                //Recipe $recipe
                $ingredients = $recipe->getIngredients();
                $get_ingredients = true;
                foreach ($ingredients as $ingredient) {
                    //Item $ingredient
                    if (!$this->hasData($ingredient->getName(), $ingredient->getAmount())) {
                        //we dont have / expired / not enough  ingredient in the fridge
                        //lets go to the next recipe
                        $get_ingredients = false;
                        break;
                    }
                }
                if ($get_ingredients) {
                    $suggestions[] = $recipe;
                }
                
            }

            if (count($suggestions) == 0){
                //no suggestions
                print_r('Order Takeout');
                return;
            }

            if (count($suggestions) == 1){
                //no suggestions
                $recipe = $suggestions[0];
               print_r('You can prepare '.$recipe->getName());
                return;
            }

            //we have more than 1 suggesion so we need to pick one by expiration of the ingredients
            $recipe = $this->getTopSuggestion($suggestions, $fridge_items);
            print_r('You can prepare '.$recipe->getName());
            return;
        }

        /**
         * Setup an array of Recipe(s)
         *
         * @param array $recipe_list the json to array parsed from the file
         * @return array of Recipe
         */
        protected function setUpCookbook($recipeData)
        {
            $recipes = array();
            $ingredients =  array();
            foreach ($recipeData as $num => $recipe_data) {
                try{
                    $recipe = new Recipe();
                    $recipe->setName($recipe_data['name']);
                    if (!isset($recipe_data['ingredients']) || count($recipe_data['ingredients']) == 0) {
                        $output->writeln("<comment>Ignoring recipe {$num}: No ingredients </comment>");
                    }
                    foreach ($recipe_data['ingredients'] as $i => $ingredient) {

                        $recipe->addIngredient($ingredient['item'], $ingredient['amount'], $ingredient['unit']);
                    }
                    $recipes[] = $recipe;
                } catch(\Exception $e) {
                   print_r("<comment>Ignoring recipe {$num}: ".$e->getMessage()."</comment>");
                }
            }
            return $recipes;
        }
        /**
     * If there are more than one suggestion here is the logic to pick one
     *
     * @param array $suggestion array of Recipe
     * @param array $fridge_items array of Item
     * @return Recipe
     */
        protected function getTopSuggestion($suggestions, $fridge_items)
        {
            $necessary_ingredients = array();
            foreach ($suggestions as $recipe_index => $recipe) {
                $ingredients = $recipe->getIngredients();
                foreach ($ingredients as $ingredient) {
                    $expiration = $this->fridgeData[$item_name]['expiration'];
                    $necessary_ingredients[$expiration][] = $recipe_index;
                }
            }
            ksort($necessary_ingredients);
            $top_recipes = reset($necessary_ingredients);

            if (count($top_recipes) == 1) {
                return $suggestions[$top_recipes[0]];
            } else {
                //we have 2 or more recipes with the same ingredient
                $possible_indexes = count($top_recipes)-1;
                return $suggestions[$top_recipes[rand(0, $possible_indexes)]];
            }
        }
              /**
         * Check if an element is available in the fridge (availability, amount, expiration)
         *
         * @param string $item_name
         * 
         */
        public function hasData($item_name, $amount)
        {
        
            date_default_timezone_set('Australia/Sydney'); 
            $amount = floatval($amount);
            if (!isset($this->fridgeData[$item_name])) {
                return false;
            }
            $item = $this->items[$item_name];
            if ($this->fridgeData[$item_name]['amount'] < $amount) {
                return false;
            }
            if ($this->fridgeData[$item_name]['expiration'] < time()) {
               return false;
            }
            return true;
        }
    }


?>