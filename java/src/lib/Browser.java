package lib;

public class Browser {
	private String app_id;
	private long date;
	
	private String browser_name;
	private String browser_version;
	private int count;
	
	public Browser(String app_id, long date){
		this.app_id = app_id;
		this.date = date;
		
		this.browser_name = new String();
		this.browser_version = new String();
		this.count = 0;
	}
	
	public void setName(String name){
		this.browser_name = name;
	}
	
	public void setVersion(String version){
		this.browser_version = version;
	}
	
	public void setCount(int count){
		this.count = count;
	}
	
	public String getName(){
		return this.browser_name;
	}
	
	public String getVersion(){
		return this.browser_version;
	}
	
	public int getCount(){
		return this.count;
	}
	
	public long getDate(){
		return this.date;
	}
	
	public String getAppID(){
		return this.app_id;
	}
}
