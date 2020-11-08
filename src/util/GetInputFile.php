
<?php
    
    
	
	require_once('src/model/Item.php') ;
    /**
     * Fridge
     * Class that has a collection of Items with some logic around if those items are available
     *
     * 
     */
    class GetInputFile
    {
        protected $items = array();
        protected $load_errors = array();
    
        /**
         * Load the fridge by a CSV file
         *
         * @param string $file the route to the file
         */
        public function loadFile($file = "")
        {
            
            
            //set the index of the file in case format change
            $colum = array(
                'name' => 0,
                'amount' => 1,
                'unit' => 2,
                'expiration' => 3
            );
            

            $data = $this->getCSVData($file);
            $finalData =  array();
            
           if (count($data) > 0) {
                foreach ($data as $key => $item_line) {
                    $finalData[$item_line[$colum['name']]]['name'] =  $item_line[$colum['name']];
                    $finalData[$item_line[$colum['name']]]['amount']= $item_line[$colum['amount']];
                    $finalData[$item_line[$colum['name']]]['unit'] =  $item_line[$colum['unit']];
                    $finalData[$item_line[$colum['name']]]['expiration'] =  $item_line[$colum['expiration']];
                    
                }
                unset($data);
            }
            return $finalData;
        }


        /**
	 * Get the data inside the CSV file
	 *
	 * @param string $file the location of the CSV
	 * @return array 
	 */
	static function getCSVData($file = "")
	{
		if (empty($file)) {
			throw new \Exception("Filename {$file} can't be empty");
			return;
		}
		$file_handle = @fopen($file, "r");
		if ($file_handle === false) {
			throw new \Exception("The file {$file} couldn't be open");
			return;
		}
		$data = array();
		while ($line = fgetcsv($file_handle)) {
			$data[] = $line;
		}
		fclose($file_handle);

		return $data;
    }
    
    	/**
	 * Get the data inside the JSON file
	 *
	 * @param string $file the location of the json file
	 * @return array 
	 */
	static function getJsonData($file = "")
	{
		if (empty($file)) {
			throw new \Exception("Filename {$file} can't be empty");
			return;
		}
		$file_content = @file_get_contents($file);
		if ($file_content === false) {
			throw new \Exception("The file {$file} couldn't be open");
			return;
		} 
		$data = json_decode($file_content,true);
		if (is_null($data) || $data === false) {
			throw new \Exception("The content of the file {$file} must be a string in JSON format");
			return;
		}
		return $data;
	}
        
  
    }

?>