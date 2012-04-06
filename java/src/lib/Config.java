package lib;

import java.io.File;
import java.io.FileNotFoundException;
import java.util.HashMap;

import org.ho.yaml.Yaml;

public class Config {
	public String host = "localhost";
	public int port = 27017;
	public String database = "analytics";
	public String configLocation = "config.yaml";
	public String locationDBLocation = "GeoLiteCity.dat";
	public boolean debug = false;
	
	public Config(String[] args){
		OptionParse options = new OptionParse(args);
	
		//want to be able to specify another location
		if(options.hasKey("--config")){
			this.configLocation = options.getKey("--config");
		}
		
		if(options.hasKey("--debug")){
			this.debug = true;
			System.out.println("Running in Debug Mode");
		}
		
		//config file takes priority over defaults
		try {
			Object configFile = Yaml.load(new File(this.configLocation));
			@SuppressWarnings("unchecked")
			HashMap<String,Object> configMap = (HashMap<String,Object>) configFile;
			
			if(configMap.get("host") != null){
				this.host = (String) configMap.get("host");
			}
			if(configMap.get("port") != null){
				this.port = ((Number) configMap.get("port")).intValue();
			}
			if(configMap.get("database") != null){
				this.database = (String) configMap.get("database");
			}
			if(configMap.get("locationDB") != null){
				this.locationDBLocation = (String) configMap.get("locationDB");
			}
		} catch (FileNotFoundException e1) {
			e1.printStackTrace();
		}
		//System.out.println(System.getProperty("user.dir"));
		
		//cli params take priority over config
		if(options.hasKey("--host")){
			this.host = options.getKey("--host");
		}
		if(options.hasKey("--port")){
			this.port = Integer.parseInt(options.getKey("--port"));
		}
		if(options.hasKey("--database")){
			this.database = options.getKey("--database");
		}
		if(options.hasKey("--locationDB")){
			this.locationDBLocation = options.getKey("--locationDB");
		}
	}

}
