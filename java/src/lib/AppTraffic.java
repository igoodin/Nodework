package lib;

public class AppTraffic {
	private String app_id;
	private long date;
	
	private int requests;
	private int uniques;
	private int visitors;
	
	public AppTraffic(String app_id, long date){
		this.app_id = app_id;
		this.date = date;
		
		this.requests = 0;
		this.uniques = 0;
		this.visitors = 0;
	}
	
	public void setRequests(int requests){
		this.requests = requests;
	}
	
	public void setVisitors(int visitors){
		this.visitors = visitors;
	}
	
	public void setUniques(int uniques){
		this.uniques = uniques;
	}
	
	public int getRequests(){
		return this.requests;
	}
	
	public int getVisitors(){
		return this.visitors;
	}
	
	public int getUniques(){
		return this.uniques;
	}
}
