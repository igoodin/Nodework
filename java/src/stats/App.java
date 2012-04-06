package stats;

import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import lib.AppTraffic;
import lib.Browser;
import lib.Globals;
import lib.Page;
import lib.Platform;
import lib.Resolution;

import com.maxmind.geoip.Location;
import com.maxmind.geoip.LookupService;
import com.mongodb.BasicDBList;
import com.mongodb.BasicDBObject;
import com.mongodb.DB;
import com.mongodb.DBCollection;
import com.mongodb.DBCursor;
import com.mongodb.DBObject;

public class App {
	private String appID;
	private DB db;
	private String[] ignoreParams;
	
	public App(String appID, DB db){
		this.appID = appID;
		this.db = db;
	}
	
	/*
	 * Logs a request event in the 'app_traffic' table
	 * 
	 * Date specific so we can run advanced queries later
	 */
	public void logRequest(long date){
		DBCollection col = this.db.getCollection("app_traffic");
		
		String dayString = Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date", dayString);
		where.put("date_nix", dayStringNix);
		
		BasicDBObject doc = new BasicDBObject();
		doc.put("$inc", new BasicDBObject("requests", 1));
		
		col.update(where, doc, true, false);
	}
	
	public void logUnique(long date){
		DBCollection col = this.db.getCollection("app_traffic");
		
		String dayString = Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date", dayString);
		where.put("date_nix", dayStringNix);
		
		BasicDBObject doc = new BasicDBObject();
		doc.put("$inc", new BasicDBObject("uniques", 1));
		
		col.update(where, doc, true, false);
	}
	
	public void logVisitor(long date){
		DBCollection col = this.db.getCollection("app_traffic");
		
		String dayString = Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date", dayString);
		where.put("date_nix", dayStringNix);
		
		BasicDBObject doc = new BasicDBObject();
		doc.put("$inc", new BasicDBObject("visitors", 1));
		
		col.update(where, doc, true, false);
	}
	
	/*
	 * Increments a pages referrer in the 'referrals' collection
	 * 
	 * Date specific so we can run advanced queries
	 */
	public void incrementReferrer(String referrer, String domain, long date){
		if(domain == null){
			domain = "";
		}
		if(referrer.indexOf(domain) == -1){
			DBCollection col = this.db.getCollection("referrals");
			
			Globals.getInternalDate(date);
			long dayStringNix = Globals.getNixDate(date);
			
			//we want to group by top-level domains since we're not doing any kind of SEO stuff 
			if(! referrer.equals("")){
				try {
					URL url = new URL(referrer);
					referrer = url.getHost();
				} catch (MalformedURLException e) {}			
			}
			
			BasicDBObject where = new BasicDBObject();
			where.put("app_id", this.appID);
			where.put("ref", referrer);
			where.put("date_nix", dayStringNix);
			
			BasicDBObject doc = new BasicDBObject();
			doc.put("$inc", new BasicDBObject("count", 1));
			
			col.update(where, doc, true, false);			
		}
	}
	
	/*
	 * Logs a browser hit in the 'browsers' table
	 * 
	 * Date specific so we can run advanced queries later
	 */
	public void logBrowser(String browser, String version, long date){
		DBCollection col = this.db.getCollection("browsers");
		
		Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("browser", browser);
		where.put("v", version);
		where.put("date_nix", dayStringNix);
		
		BasicDBObject doc = new BasicDBObject();
		doc.put("$inc", new BasicDBObject("count", 1));
		
		col.update(where, doc, true, false);
	}
	
	/*
	 * Logs a mobile request in the 'mobiles' table
	 * 
	 * Date specific so we can run advanced queries later
	 */
	public void logMobile(String mobile, long date){
		DBCollection col = this.db.getCollection("mobiles");
		
		Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("platform", mobile);
		where.put("date_nix", dayStringNix);
		
		BasicDBObject doc = new BasicDBObject();
		doc.put("$inc", new BasicDBObject("count", 1));
		
		col.update(where, doc, true, false);
	}
	
	/*
	 * Logs a platform hit in the 'platforms' collection
	 * 
	 * Date specific so we can run advanced queries
	 */
	public void logPlatform(String platform, long date){
		DBCollection col = this.db.getCollection("platforms");
		
		Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("platform", platform);
		where.put("date_nix", dayStringNix);
		
		BasicDBObject doc = new BasicDBObject();
		doc.put("$inc", new BasicDBObject("count", 1));
		
		col.update(where, doc, true, false);
	}
	
	/*
	 * Logs a lat/long based on IP address (if available) in the 'loc' collection
	 * 
	 * Date specific so we can run advanced queries
	 */
	public void logLocation(String ipAddress, LookupService ls, long date){
		DBCollection col = this.db.getCollection("loc");
		
		Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		Location l = ls.getLocation(ipAddress);

		if(l != null){
			float latitude = l.latitude;
			float longitude = l.longitude;

			BasicDBObject where = new BasicDBObject();
			where.put("app_id", this.appID);
			where.put("date_nix", dayStringNix);
			where.put("lat", latitude);
			where.put("long", longitude);
			
			BasicDBObject update = new BasicDBObject();
			update.put("$inc", new BasicDBObject("count", 1));

			col.update(where, update, true, false);
		}
	}
	
	/*
	 * Logs a resolution hit in the 'resolutions' collection
	 * 
	 * Date specific so we can run advanced queries later
	 */
	public void logResolution(String resolution, long date){
		DBCollection col = this.db.getCollection("resolutions");
		
		Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("res", resolution);
		where.put("date_nix", dayStringNix);
		
		BasicDBObject update = new BasicDBObject();
		update.put("$inc", new BasicDBObject("count", 1));
		
		col.update(where, update, true, false);
	}

	/*
	 * This increments the page count that allows us to create the
	 * chart of most popular pages on a site.
	 * 
	 * It is date specific so that we can run more advanced queries on the data later.
	 */
	public void logPage(String loc, long date) {
		DBCollection col = this.db.getCollection("pages");
		
		loc = this.filterPage(loc);
		
		Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("page", loc);
		where.put("date_nix", dayStringNix);
		
		BasicDBObject update = new BasicDBObject();
		update.put("$inc", new BasicDBObject("count", 1));
		
		col.update(where, update, true, false);
	}
	

	public void logTime(long date) {
		DBCollection col = this.db.getCollection("times");
		
		Globals.getInternalDate(date);
		long dayStringNix = Globals.getNixDate(date);
		
		Calendar c = Calendar.getInstance();
		c.setTimeInMillis(date);
		
		int day_of_week = c.get(Calendar.DAY_OF_WEEK);
		int hour_of_day = c.get(Calendar.HOUR_OF_DAY);
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date_nix", dayStringNix);
		where.put("eventat", day_of_week + "::" + hour_of_day);
		
		BasicDBObject update = new BasicDBObject();
		update.put("$inc", new BasicDBObject("count", 1));
		
		col.update(where, update, true, false);
	}

	private String filterPage(String loc) {
		try {
			URL url = new URL(loc);
			String query = url.getQuery();
			if(query == null){return loc;}
			
			String[] params = query.split("&");
			ArrayList<String> goodParams = new ArrayList<String>();
			for(int i = 0; i < params.length; i++){
				String[] split = params[i].split("=");
				if(split.length > 0 && !Globals.inArray(split[0], this.ignoreParams)){
					goodParams.add(params[i]);
				}
			}
			
			Object[] arrGoodParamsObj = goodParams.toArray();
			Arrays.sort(arrGoodParamsObj);
			
			String qs = new String();
			for(int i = 0; i < arrGoodParamsObj.length; i++){
				qs += arrGoodParamsObj[i];
				if(i != arrGoodParamsObj.length -1){
					qs += "&";
				}
			}
			
			Pattern p = Pattern.compile("[?].+$");
			Matcher m = p.matcher(loc);
			
			if(qs.length() == 0){
				return m.replaceAll("");
			}
			return m.replaceAll("?" + qs);
		} catch (MalformedURLException e) {
			return null;
		}
	}

	public String[] getIgnoreParams() {
		DBCollection col = this.db.getCollection("app_settings");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		
		DBObject record = col.findOne(where);
		if(record == null){
			return new String[0];
		}
		else{
			Object[] params = ((BasicDBList) record.get("app_params")).toArray();
			String[] strParams = new String[params.length];
			for(int i = 0; i < params.length; i++){
				strParams[i] = params[i].toString();
			}
			
			return strParams;
		}
	}
	
	public String getDomain() {
		DBCollection col = this.db.getCollection("app_settings");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		
		DBObject record = col.findOne(where);
		if(record == null){
			return new String();
		}
		else{
			return (String) record.get("app_domain");
		}
	}
	
	public void setIgnoreParams(String[] ignoreParams) {
		this.ignoreParams = ignoreParams;
	}
	
	////////////////////
	
	/*
	 * There's only one app_traffic entry per app per day
	 */
	public AppTraffic getAppTraffic(long date){
		DBCollection app_traffic = this.db.getCollection("app_traffic");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date_nix", date);
		
		DBObject record = app_traffic.findOne();
		if(record == null){
			return null;
		}
		
		AppTraffic at = new AppTraffic(this.appID, date);
		
		if( record.get("requests") != null){
			at.setRequests(((Number) record.get("requests")).intValue());
		}
		if( record.get("uniques") != null){
			at.setUniques(((Number) record.get("uniques")).intValue());
		}
		if( record.get("visitors") != null){
			at.setVisitors(((Number) record.get("visitors")).intValue());
		}
		
		return at;
	}
	
	public Browser[] getbrowsers(long date){
		DBCollection browsers = this.db.getCollection("browsers");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date_nix", date);
		
		DBCursor cur = browsers.find(where);
		
		Browser b[] = new Browser[cur.size()];
		
		int index = 0;
		while(cur.hasNext()){
			DBObject record = cur.next();
			
			b[index] = new Browser(this.appID, date);
			if(record.get("browser") != null){
				b[index].setName((String) record.get("browser"));
			}
			if(record.get("version") != null){
				b[index].setVersion((String) record.get("version"));
			}
			if(record.get("count") != null){
				b[index].setCount(((Number) record.get("count")).intValue());
			}
			
			index++;
		}
		
		return b;
	}
	
	public Platform[] getMobiles(long date){
		DBCollection mobiles = this.db.getCollection("mobiles");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date_nix", date);
		
		DBCursor cur = mobiles.find(where);
		
		Platform m[] = new Platform[cur.size()];
		
		int index = 0;
		while(cur.hasNext()){
			DBObject record = cur.next();
			
			m[index] = new Platform(this.appID, date);
			if(record.get("platform") != null){
				m[index].setPlatform((String) record.get("platform"));
			}
			if(record.get("count") != null){
				m[index].setCount(((Number) record.get("count")).intValue());
			}
			
			index++;
		}
		
		return m;
	}
	
	public Page[] getPages(long date){
		DBCollection pages = this.db.getCollection("pages");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date_nix", date);
		
		DBCursor cur = pages.find(where);
		
		Page p[] = new Page[cur.size()];
		
		int index = 0;
		while(cur.hasNext()){
			DBObject record = cur.next();
			
			p[index] = new Page(this.appID, date);
			if(record.get("page") != null){
				p[index].setPage((String) record.get("page"));
			}
			if(record.get("count") != null){
				p[index].setCount(((Number) record.get("count")).intValue());
			}
			
			index++;
		}
		
		return p;
	}
	
	public Platform[] getPlatforms(long date){
		DBCollection platforms = this.db.getCollection("platforms");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date_nix", date);
		
		DBCursor cur = platforms.find(where);
		
		Platform p[] = new Platform[cur.size()];
		
		int index = 0;
		while(cur.hasNext()){
			DBObject record = cur.next();
			
			p[index] = new Platform(this.appID, date);
			if(record.get("platform") != null){
				p[index].setPlatform((String) record.get("platform"));
			}
			if(record.get("count") != null){
				p[index].setCount(((Number) record.get("count")).intValue());
			}
			
			index++;
		}
		
		return p;
	}
	
	public Resolution[] getResolutions(long date){
		DBCollection resolutions = this.db.getCollection("resolutions");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date_nix", date);
		
		DBCursor cur = resolutions.find(where);
		
		Resolution r[] = new Resolution[cur.size()];
		
		int index = 0;
		while(cur.hasNext()){
			DBObject record = cur.next();
			
			r[index] = new Resolution(this.appID, date);
			if(record.get("res") != null){
				r[index].setResolution((String) record.get("res"));
			}
			if(record.get("count") != null){
				r[index].setCount(((Number) record.get("count")).intValue());
			}
			
			index++;
		}
		
		return r;
	}
	
	public Page[] getReferrals(long date){
		DBCollection pages = this.db.getCollection("referrals");
		
		BasicDBObject where = new BasicDBObject();
		where.put("app_id", this.appID);
		where.put("date_nix", date);
		
		DBCursor cur = pages.find(where);
		
		Page p[] = new Page[cur.size()];
		
		int index = 0;
		while(cur.hasNext()){
			DBObject record = cur.next();
			
			p[index] = new Page(this.appID, date);
			if(record.get("ref") != null){
				p[index].setPage((String) record.get("ref"));
			}
			if(record.get("count") != null){
				p[index].setCount(((Number) record.get("count")).intValue());
			}
			
			index++;
		}
		
		return p;
	}

}
