<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Mango friendly extension to the model class
*
* Because of the way the mangoci library works, everytime a function is called
* in a model, the mongo collection should be re-selected.  If two mdels (A & B)
* are loaded, and calles are made in the order ABA, then the second model call
* to A would be trying to touch the collection of B.  This extention introduces
* a fix.  I put it in an extension so I wouldn't have to redefine it in each
* model.  Also, extend Model and not MY_Model if you don't use Mango and
* just want to use CI's ActiveRecors,etc.
*/
class MY_Model extends Model{

	/**
	* Class constructor
	*
	* @access public
	*/
	public function MY_Model(){
		parent::Model();
	}

	/**
	* Selects a mongo collection using mangoci
	*
	* The value of $this->collection is set in the constructors of the
	* models that extend this class.
	*
	* @access public
	*/
    public function mangoSelectCollection(){
		$this->mangoci->selectCollection($this->collection);
	}
}

/* End of file MY_Model.php */
