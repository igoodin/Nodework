<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* MangoCI - CodeIgniter library for interacting with MongoDB
*
* @author David Wischhusen
*/
class MangoCI{
//////////////////////////// CONSTRUCTOR / Mongo	
	const DB_ERROR = 'No Database Selected';
	const COLLECTION_ERROR = 'Collection Undefined';
	const CURSOR_ERROR = 'Cursor Undefined';

	/**
	* Class constructor, creates connection to Mongo instance
	*
	* @access public
	*/
	public function __construct(){
		$this->mongo_collection = NULL;
		$this->mongo_db = NULL;
		
		$this->resetCollection();
		$this->resetCursor();

		$this->_CI = &get_instance();

		$this->HOST = $this->_CI->config->item('mongodb_host');
		$this->PORT = $this->_CI->config->item('mongodb_port');
		$this->DEFAULT_DB = $this->_CI->config->item('mongodb_default_db');

		$conn = "{$this->HOST}:{$this->PORT}";
		try{
			$this->mongo_conn = new Mongo( $conn );
		}
		catch(MongoConnectionException $e){
			//should clean up this error
			die("Unable to Connect to MongoDB...Exiting");
		}

		$this->selectDB( $this->DEFAULT_DB );
	}
	
	/**
	* Returns the connection to MongoDB
	*
	* @access public
	* @return Mongo
	*/
	public function getConnection(){
		return $this->mongo_conn;
	}

////////////////////////////////// MongoDB
	/**
	* Selects the database
	*
	* @access public
	* @param string
	*/
	public function selectDB($dbname){
		$this->mongo_db = $this->mongo_conn->$dbname;
	}
	
	/**
	* Returns the selected database object
	*
	* @access public
	* @return MongoDB
	*/
	public function getDB(){
		if(! is_null($this->mongo_db)){
			return $this->mongo_db;
		}
		else{
			trigger_error(self::DB_ERROR);
		}
	}

//////////////////////////// MongoCollection
	/**
	* Selects the collection
	*
	* @access public
	* @param string
	*/
	public function selectCollection($collection_name){
		if(! is_null($this->mongo_db)){
			$this->mongo_collection = $this->mongo_db->$collection_name;
		}
		else{
			trigger_error(self::DB_ERROR);
		}
	}
	
	/**
	* Returns the currently selected collection object
	*
	* @access public
	* @return MongoCollection
	*/
	public function getCollection(){
		if(! is_null($this->mongo_collection)){
			return $this->mongo_collection;
		}
		else{
			trigger_error(self::COLLECTION_ERROR);
		}
	}

	/**
	* Executes a search of the current collection
	*
	* @access public
	*/
	public function find(){
		if(! is_null($this->mongo_collection)){
			if(is_null($this->constraints)){$this->constraints = array();}
			if(is_null($this->fields)){$this->fields = array();}
			$this->mongo_cursor = $this->mongo_collection->find($this->constraints, $this->fields);
		}
		else{
			trigger_error(self::COLLECTION_ERROR);
		}
		$this->resetCollection();
	}
	
	/**
	* Finds the first document in a collection matching an optional set of constraints
	*
	* @access public
	* @return array
	*/
	public function findOne(){
		if( !is_null($this->mongo_collection)){
			if(is_null($this->constraints)){$this->constraints = array();}
			if(is_null($this->fields)){$this->fields = array();}
			$record = $this->mongo_collection->findOne($this->constraints, $this->fields);
			$this->resetCollection();
			return $record;
		}
		else{
			trigger_error(self::COLLECTION_ERROR);
		}
		$this->resetCollection();
	}

	/**
	* Inserts a document into the current collection
	*
	* @access public
	* @param array
	*/
	public function insert($data){
		if(! is_null($this->mongo_collection)){
			$this->mongo_collection->insert( $data );
		}
		else{
			trigger_error(self::COLLECTION_ERROR);
		}
	}

	/**
	* Creates an index on a field in the current collection
	*
	* @access public
	* @param string
	* @param array
	*/
	public function index($field, $options=array()){
		if(! is_null($this->mongo_collection)){
			$this->mongo_collection->ensureIndex($field, $options);
		}
		else{
			trigger_error(self::COLLECTION_ERROR);
		}
	}
	
	/**
	* Updates documents matching required constraints with provided object
	*
	* @access public
	* @param array
	* @param array
	*/
	public function update($new_obj, $options = array(), $modifier=FALSE){
		if(! is_null($this->mongo_collection)){
			if(! is_null($this->constraints)){
				if(! $modifier){
					$this->mongo_collection->update($this->constraints, array('$set' => $new_obj), $options);
				}
				else{
					$this->mongo_collection->update($this->constraints, array($modifier => $new_obj), $options);
				}
			}
			else{
				trigger_error('No Constraints Provided');
			}
		}
		else{
			trigger_error(self::COLLECTION_ERROR);
		}
		$this->resetCollection();
	}

	/**
	* Removes items from the collection
	*
	* @access public
	* @param array
	*/
	public function remove($options = array()){
		if(! is_null($this->mongo_collection)){
			if(! is_null($this->constraints)){
				$this->mongo_collection->remove($this->constraints, $options);
			}
			else{
				trigger_error('No Constraints Provided');
			}
		}
		else{
			trigger_error(self::COLLECTION_ERROR);
		}
		$this->resetCollection();
	}
	
	/**
	* Returns the number of documents in the current collection
	*
	* @access public
	* @return int
	*/
	public function num_documents(){
		if(! is_null($this->mongo_collection)){
			return $this->mongo_collection->count();
		}
		else{
			trigger_error(self::COLLECTION_ERROR);
		}
	}

	/**
	* Sets the constraints for find/findOne/update functions
	*
	* @access public
	* @param array
	*/
	public function constraints($constraints){
		$this->constraints = $constraints;
	}

	/**
	* Sets the fields to select from the find/findOne functions
	*
	* @access public
	* @param array
	*/
	public function fields($fields){
		$this->fields = $fields;
	}

	/**
	* Resets current constraints & fields to null
	*
	* @access private
	*/	
	private function resetCollection(){
		$this->constraints = NULL;
		$this->fields = NULL;
	}

///////////////////////////// MongoCursor

	/**
	* Fetch the results from the cursor after a call to the find function
	*
	* @access public
	* @return array
	*/
	public function results(){
		if(! is_null($this->mongo_cursor)){
			$results = array();
			while($this->mongo_cursor->hasNext()){
				$results[] = $this->mongo_cursor->getNext();
			}
			$this->resetCollection();
			return $results;
		}
		else{
			trigger_error(self::CURSOR_ERROR);
		}
		$this->resetCollection();
		$this->resetCursor();
	}
	
	/**
	* Count the number of results in the current cursor (created in find function)
	*
	* @access public
	* @param bool
	* @return int
	*/
	public function count_all_results($all=false){
		if(! is_null($this->mongo_cursor)){
			$count = $this->mongo_cursor->count($all);
			$this->resetCollection();
			return $count;
		}
		else{
			trigger_error(self::CURSOR_ERROR);
		}
		$this->resetCollection();
		$this->resetCursor();
	}

	/**
	* Sets a sort condition for the current cursor
	*
	* @access public
	* @param array
	*/
	public function sort($fields){
		if(! is_null($this->mongo_cursor)){
			$this->mongo_cursor->sort($fields);
		}
		else{
			trigger_error(self::CURSOR_ERROR);
		}
	}

	/**
	* Sets a limit on the current cursor
	*
	* @access public
	* @param int
	*/
	public function limit($num){
		if(! is_null($this->mongo_cursor)){
			$this->mongo_cursor->limit($num);
		}
		else{
			trigger_error(self::CURSOR_ERROR);
		}
	}
	
	/**
	* Return the current cursor object
	*
	* @access public
	* @return MongoCursor
	*/
	public function getCursor(){
		if(! is_null($this->mongo_cursor)){
			return $this->mongo_cursor;
		}
		else{
			trigger_error(self::CURSOR_ERROR);
		}
	}


	/**
	* Resets the cursor to null
	*
	* @access private
	*/
	private function resetCursor(){
		$this->mongo_cursor = NULL;
	}
}

/* End of file mongoci.php */
