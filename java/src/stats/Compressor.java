package stats;

import java.util.Calendar;
import java.util.HashMap;
import java.util.Iterator;

import lib.AppTraffic;
import lib.Browser;
import lib.Globals;
import lib.OldestRecord;
import lib.Page;
import lib.Platform;
import lib.Resolution;

import com.mongodb.BasicDBObject;
import com.mongodb.DB;
import com.mongodb.DBCollection;
import com.mongodb.DBCursor;
import com.mongodb.DBObject;

public class Compressor {
	private DB db;
	
	public Compressor(DB db){
		this.db = db;
	}
	
	/*
	 * Start assembling old data, 1 day at a time and compressing
	 * into a collection where that days information can be accessed much more quickly.
	 */
	public void start(){
		System.out.println("Starting record compression");
		
		OldestRecord oldest_record = this.getOldestDate();
		
		if(oldest_record == null){
			return;
		}
		
		String oldest_id = oldest_record.getAppID();
		long oldest_date = oldest_record.getAppDate();
		Calendar c = Calendar.getInstance();
		
		if(Globals.isToday(oldest_date)){
			System.out.println("Is Today");
			return;
		}
		else{
			System.out.println("Is Not Today");
		}
		
		//it's not today and we know that pings are empty
		//gather the data
		App a = new App(oldest_id, this.db);
		
		AppTraffic at = a.getAppTraffic(oldest_date);
		Browser browsers[] = a.getbrowsers(oldest_date);
		Platform mobiles[] = a.getMobiles(oldest_date);
		Page pages[] = a.getPages(oldest_date);
		Platform platforms[] = a.getPlatforms(oldest_date);
		Page referrals[] = a.getReferrals(oldest_date);
		Resolution resolutions[] = a.getResolutions(oldest_date);
		
		//app traffic is already formatted
		
		//@todo browsers_hash
		
		HashMap<String, Integer> mobiles_hash = new HashMap<String, Integer>();
		for(int i = 0; i < mobiles.length; i++){
			String mobile_platform = mobiles[i].getPlatform();
			int mobile_count = mobiles[i].getCount();
			
			int map_count = 0;
			if(mobiles_hash.containsKey(mobile_platform)){
				map_count = (Integer) mobiles_hash.get(mobile_platform);
			}
			map_count += mobile_count;
			
			mobiles_hash.put(mobile_platform, map_count);
		}
		
		HashMap<String, Integer> pages_hash = new HashMap<String, Integer>();
		for(int i = 0; i < pages.length; i++){
			String page_href = pages[i].getPage();
			int page_count = pages[i].getCount();
			
			int map_count = 0;
			if(pages_hash.containsKey(page_href)){
				map_count = (Integer) pages_hash.get(page_href);
			}
			map_count += page_count;
			
			pages_hash.put(page_href, map_count);
		}
		
		HashMap<String, Integer> platforms_hash = new HashMap<String, Integer>();
		for(int i = 0; i < platforms.length; i++){
			String platform_name = platforms[i].getPlatform();
			int platform_count = platforms[i].getCount();
			
			int map_count = 0;
			if(platforms_hash.containsKey(platform_name)){
				map_count = (Integer) platforms_hash.get(platform_name);
			}
			map_count += platform_count;
			
			platforms_hash.put(platform_name, map_count);
		}
		
		HashMap<String, Integer> referrals_hash = new HashMap<String, Integer>();
		for(int i = 0; i < referrals.length; i++){
			String page_href = referrals[i].getPage();
			int page_count = referrals[i].getCount();
			
			int map_count = 0;
			if(referrals_hash.containsKey(page_href)){
				map_count = (Integer) referrals_hash.get(page_href);
			}
			map_count += page_count;
			
			referrals_hash.put(page_href, map_count);
		}
		
		HashMap<String, Integer> resolutions_hash = new HashMap<String, Integer>();
		for(int i = 0; i < resolutions.length; i++){
			String resolution = resolutions[i].getResolution();
			int resolution_count = resolutions[i].getCount();
			
			int map_count = 0;
			if(resolutions_hash.containsKey(resolution)){
				map_count = (Integer) resolutions_hash.get(resolution);
			}
			map_count += resolution_count;
			
			resolutions_hash.put(resolution, map_count);
		}
		
		//prepare to insert the actual record
		DBCollection compressed_records = db.getCollection("compressed_records");
		BasicDBObject compressed = new BasicDBObject();
		
		compressed.put("app_id", oldest_id);
		compressed.put("date", oldest_date);
		
		BasicDBObject db_app_traffic = new BasicDBObject();
		db_app_traffic.put("requests", at.getRequests());
		db_app_traffic.put("uniques", at.getUniques());
		db_app_traffic.put("visitors", at.getVisitors());
		compressed.put("traffic", db_app_traffic);
		
		BasicDBObject db_mobiles = new BasicDBObject();
		Iterator<String> mobiles_key_iterator = mobiles_hash.keySet().iterator();
		while(mobiles_key_iterator.hasNext()){
			String key = (String) mobiles_key_iterator.next();
			int key_count = mobiles_hash.get(key);
			db_mobiles.put(key, key_count);
		}
		compressed.put("mobiles", db_mobiles);
		
		BasicDBObject db_pages = new BasicDBObject();
		Iterator<String> pages_key_iterator = pages_hash.keySet().iterator();
		while(pages_key_iterator.hasNext()){
			String key = (String) pages_key_iterator.next();
			int key_count = pages_hash.get(key);
			db_pages.put(key, key_count);
		}
		compressed.put("pages", db_pages);
		
		BasicDBObject db_platforms = new BasicDBObject();
		Iterator<String> platforms_key_iterator = platforms_hash.keySet().iterator();
		while(platforms_key_iterator.hasNext()){
			String key = (String) platforms_key_iterator.next();
			int key_count = platforms_hash.get(key);
			db_platforms.put(key, key_count);
		}
		compressed.put("platforms", db_platforms);
		
		BasicDBObject db_referrals = new BasicDBObject();
		Iterator<String> referrals_key_iterator = referrals_hash.keySet().iterator();
		while(platforms_key_iterator.hasNext()){
			String key = (String) referrals_key_iterator.next();
			int key_count = referrals_hash.get(key);
			db_referrals.put(key, key_count);
		}
		compressed.put("referrals", db_referrals);
		
		BasicDBObject db_resolutions = new BasicDBObject();
		Iterator<String> resolutions_key_iterator = resolutions_hash.keySet().iterator();
		while(resolutions_key_iterator.hasNext()){
			String key = (String) resolutions_key_iterator.next();
			int key_count = resolutions_hash.get(key);
			db_resolutions.put(key, key_count);
		}
		compressed.put("resolutions", db_resolutions);
		
		System.out.println("inserting compressed record");
		compressed_records.insert(compressed);
		
		//@todo delete old records
	}
	
	/*
	 * Looks in the 'app_traffic' collection for the oldest record
	 */
	private OldestRecord getOldestDate(){
		DBCollection app_traffic = this.db.getCollection("app_traffic");
		
		BasicDBObject sortBy = new BasicDBObject("date_nix", 1);
		
		DBCursor cur = app_traffic.find().sort(sortBy);
		
		OldestRecord or = null;
		if(cur.hasNext()){
			DBObject record = cur.next();
			String app_id = (String) record.get("app_id");
			//System.out.println(record.get("date_nix"));
			long app_date = ((Number) record.get("date_nix")).longValue();
			or = new OldestRecord(app_id, app_date);
		}
		
		return or;
	}
	
//	private boolean pingsExist(String app_id, long record_date){
//		String js_date = Globals.getJSDate(record_date);
//		
//		DBCollection pings = this.db.getCollection("pings");
//		
//		BasicDBObject where = new BasicDBObject();
//		where.put("app_id", app_id);
//		where.put("human_date", js_date);
//		
//		if(pings.find(where).count() > 0){
//			return true;
//		}
//		return false;
//	}
}
