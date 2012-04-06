import java.io.FileWriter;
import java.io.IOException;
import java.net.UnknownHostException;
import java.util.HashMap;

import org.bson.types.ObjectId;

import stats.App;
import stats.Compressor;
import stats.Ping;

import lib.Config;
import com.maxmind.geoip.LookupService;
import com.mongodb.BasicDBObject;
import com.mongodb.DBCollection;
import com.mongodb.DBObject;
import com.mongodb.Mongo;
import com.mongodb.DB;
import com.mongodb.MongoException;

public class Ping_launchpad {
	public static void main(String[] args) {
		Config c = new Config(args);
		FileWriter logFile = null;
		
		//connect to our IP locator database
		LookupService ls = null;
		try {
			ls = new LookupService(c.locationDBLocation);
		} catch (IOException e2) {
			e2.printStackTrace();
		}
		
		if(c.debug){
			try {
				logFile = new FileWriter("../log.txt", true);
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		
		System.out.println("Connecting to " + c.database + " on " + c.host + ":" + c.port);
		
		HashMap<String, String[]> cachedIgnoreParams = new HashMap<String, String[]>();
		HashMap<String, String> cachedDomains = new HashMap<String, String>();
		
		boolean tryConnect = true;
		
		Mongo m;
		while(tryConnect){
			try {
				m = new Mongo(c.host, c.port);
				DB db = m.getDB(c.database);
				DBCollection pings = db.getCollection("pings");
				Compressor compressor = new Compressor(db);
				while(true){
					//wait for a certain amount of time if there are no pings left
					while(pings.count() == 0){
						try {
							if(c.debug){
								System.out.println("No Pings, sleeping...");								
							}
							//compressor.start();
							Thread.sleep(1500); // 1.5 seconds
						} catch (InterruptedException e) {
							e.printStackTrace();
						}
					}
					
					DBObject record = pings.findOne();
					
					ObjectId id = (ObjectId) record.get("_id");
					BasicDBObject deleteWhere = new BasicDBObject("_id", id);
					pings.remove(deleteWhere);
					
					if(c.debug){
						try {
							logFile.write(record.toString() + "\n");
							logFile.flush();
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
					}
					
					String appID = (String) record.get("app_id");
					App a = new App(appID, db);
					String[] ignoreParams; //what to remove/ignore from query string
					String domain; // used for referrer
					
					//check for cached params
					if(cachedIgnoreParams.get(appID) != null){ //hit
						ignoreParams = cachedIgnoreParams.get(appID);
					}
					else{ //miss
						ignoreParams = a.getIgnoreParams();
						cachedIgnoreParams.put(appID, ignoreParams);
					}
					
					//check for cached domain
					if(cachedDomains.get(appID) != null){ //hit
						domain = cachedDomains.get(appID);
					}
					else{ //miss
						domain = a.getDomain();
						cachedDomains.put(appID, domain);
					}
				
					Ping p = new Ping(record, db, ls);
					p.process(ignoreParams, domain);
				}
			} catch (UnknownHostException e) {
				if(c.debug){
					System.out.println("Unable to connect to Mongo @" + c.host + ":" + c.port);					
				}
				try {
					System.out.println("No Pings, sleeping...");
					Thread.sleep(1500); // 1.5 seconds
				} catch (InterruptedException e1) {
					e1.printStackTrace();
				}
			} catch (MongoException e) {
				if(c.debug){
					System.out.println("Unable to connect to Mongo @" + c.host + ":" + c.port);					
				}
				try {
					System.out.println("No Pings, sleeping...");
					Thread.sleep(1500); // 1.5 seconds
				} catch (InterruptedException e2) {
					e.printStackTrace();
				}
			}
		}
	}
}
