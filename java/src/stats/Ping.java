package stats;

import java.io.IOException;

import lib.Globals;

import com.maxmind.geoip.LookupService;
import com.mongodb.DB;
import com.mongodb.DBObject;

public class Ping {
	private DBObject record;
	private DB db;
	private LookupService ls;
	
	public Ping(DBObject record, DB db, LookupService ls){
		this.record = record;
		this.db = db;
		this.ls = ls;
	}
	
	public void process(String[] ignoreParams, String domain){
		//pings record
		String appID = (String) this.record.get("app_id");
		long date = ((Number) this.record.get("date")).longValue();
		String loc = (String) this.record.get("loc");
		String ref = (String) this.record.get("ref");
		String browser = (String) this.record.get("browser");
		String version = (String) this.record.get("version");
		boolean ismobile = (Boolean) this.record.get("ismobile");
		String platform = (String) this.record.get("platform");
		String ip = (String) this.record.get("ip");
		String res = (String) this.record.get("res");
		boolean unique = (Boolean) this.record.get("unique");
		boolean visitor = (Boolean) this.record.get("visitor");

		//Update our app statistics
		App app = new App(appID, this.db);
		app.setIgnoreParams(ignoreParams);
		if(!ip.isEmpty()){
			app.logLocation(ip,this.ls,date);
		}
		app.logPage(loc, date);
		app.logRequest(date);
		app.logTime(date);
		
		//check if our user is unique
		if(unique){
			app.logUnique(date);
			if(ismobile){
				app.logMobile(browser, date);
			}else{
				app.logBrowser(browser, version, date);				
			}
			if(platform!=null){
				app.logPlatform(platform, date);
			}
			app.logResolution(res, date);
			if(ls != null){
				app.logLocation(ip, this.ls, date);
			}
		}
		else if(visitor){
			app.logVisitor(date);
		}
		
		//update the referrer collection
		if(ref != null){
			app.incrementReferrer(ref, domain, date);			
		}
	}
}
