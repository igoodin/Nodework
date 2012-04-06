package lib;

/**
 * Provides an interface to process command line arguments
 */
public class OptionParse {
	private String[] options;
	
	/**
	 * Constructor
	 * 
	 * @param args The command line arguments
	 */
	public OptionParse(String[] args){
		this.options = args;
	}

	/**
	 * Checks for the presence of a given command line parameter
	 * 
	 * @param key The command line flag to search for
	 * @return If the key is present
	 */
	public boolean hasKey(String key){
		for(int i = 0; i < this.options.length; i++){
			if(this.options[i].equals(key)){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Returns the value of a given command line option
	 * 
	 * @param key The key whose value to return
	 * @return The value of the given key
	 */
	public String getKey(String key){
		String value = new String();
		if(this.hasKey(key)){
			for(int i = 0; i < this.options.length; i++){
				if(this.options[i].equals(key)){
					for(int j = i +1; j < this.options.length; j++){
						if(! this.options[j].startsWith("-")){
							if(value.equals("")){
								value += this.options[j];
							}
							else{
								value += (" " + this.options[j]);
							}
						}
						else{
							return value;
						}
					}
				} 
			}
		}
		return value;
	}
	
	/**
	 * Returns the last parameter given
	 * 
	 * @return The last command line parameter
	 */
	public String getLast(){
		return this.options[this.options.length -1];
	}
	
	/**
	 * Returns a string representation of this object
	 */
	public String toString(){
		String args = new String();
		for(int i = 0; i < this.options.length; i++){
			args += (options[i] + " ");
		}
		return args;
	}
}