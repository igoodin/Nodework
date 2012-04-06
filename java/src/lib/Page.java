package lib;

public class Page {
	private String app_id;
	private long date;
	
	private int count;
	private String page;
	
	public Page(String app_id, long date){
		this.app_id = app_id;
		this.date = date;
		
		this.count = 0;
		this.page = new String();
	}
	
	public void setPage(String page){
		this.page = page;
	}
	
	public void setCount(int count){
		this.count = count;
	}
	
	public String getPage(){
		return this.page;
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
