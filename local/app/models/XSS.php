<?php
/**
* Written by: WAIYL KARIM
* EMAIL: karimwaiyl@gmail.com
*/

class xss extends Request
{
	public $_data 					= [];
	protected $_options 		= [];
	public $_erroredFields	= [];
	public $_errors					= 0;
	public $_object;

	/**
	* Cleans the input
	* @param array form input
	* @return Response
	*/

	public function clean(array $e)
	{
		if (is_array($e)) {
			if (!empty($e)) {
				// Loop through the given data
				foreach($e AS $key => $value) 
				{
					// Assign appropriate PHP FILTER
					switch ($value) {
						case 'str':
							$filter = FILTER_SANITIZE_STRING;	
							break;
						case 'int':
							$filter = FILTER_VALIDATE_INT;
							break;
						case 'email':
							$filter = FILTER_VALIDATE_EMAIL;
							break;
						case 'boolean':
							$filter = FILTER_VALIDATE_BOOLEAN;
							break;
						case 'float':
							$filter = FILTER_VALIDATE_FLOAT;
							break;
						case 'ip':
							$filter = FILTER_VALIDATE_IP;
							break;
						case 'url':
							$filter = FILTER_VALIDATE_URL;
							break;
						
						default:
							$filter = FILTER_SANITIZE_STRING; 
							break;
					}
					
					// Gather everything
					$this->_options[$key] = $filter;
					$this->_data = filter_input_array(INPUT_POST, $this->_options);
				}

				// Check for errors
				if ($this->_data != null || !empty($this->_data)) {
					foreach ($this->_options as $key => $value) {
						// Check if fields are empty or invalid
						if (empty(Input::get($key)) || ($this->_data[$key] == false)) {
							$this->_errors++; 
							$this->_erroredFields[$key] = $key;
							return NULL;
						}
					}
				}
			}
		}
		$this->_object = $this->toObject($this->get());
	}

	/**
	* Checks if the validation passes
	* @return Response
	*/
	public function passes()
	{
		return $this->_errors == 0 
			&& empty($this->_erroredFields) 
				&& !is_null($this->_data)
					? 1
					: 0;
	}

	/**
	* Get the output filtered data
	* @return Response
	*/
	public function get()
	{
		if ($this->passes()) {
			if (!is_null($this->_object)) {
				return $this->_object;
			}
			return $this->_data;
		}
		return false;
	}

	/**
	* Convert array into object
	* @param array
	* @return object
	*/
	public function toObject(array $array)
	{
		if (is_array($array)) {
			if (!empty($array)) {
				$object = new stdClass();
				foreach ($array as $key => $value) {
				  $object->$key = $value;
				}
				return $object;
			}
		}
	}
}
