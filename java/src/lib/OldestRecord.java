package lib;

public class OldestRecord {
	public String app_id;
	public long app_date;
	
	public OldestRecord(String app_id, long app_date){
		this.app_id = app_id;
		this.app_date = app_date;
	}
	
	public String getAppID(){
		return this.app_id;
	}
	
	public long getAppDate(){
		return this.app_date;
	}
}
