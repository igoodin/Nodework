package lib;

public class Platform {
	private String app_id;
	private long date;
	
	private int count;
	private String platform;
	
	public Platform(String app_id, long date){
		this.app_id = app_id;
		this.date = date;
		
		this.count = 0;
		this.platform = new String();
	}
	
	public void setPlatform(String page){
		this.platform = page;
	}
	
	public void setCount(int count){
		this.count = count;
	}
	
	public String getPlatform(){
		return this.platform;
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

