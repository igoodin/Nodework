package lib;

public class Resolution {
	private String app_id;
	private long date;
	
	private int count;
	private String resolution;
	
	public Resolution(String app_id, long date){
		this.app_id = app_id;
		this.date = date;
		
		this.count = 0;
		this.resolution = new String();
	}
	
	public void setResolution(String resolution){
		this.resolution = resolution;
	}
	
	public void setCount(int count){
		this.count = count;
	}
	
	public String getResolution(){
		return this.resolution;
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
