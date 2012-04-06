package lib;

import java.util.Calendar;
import java.util.Random;

public class Globals {
	public static String getInternalDate(long milliseconds){
		Calendar cal = Calendar.getInstance();
		cal.setTimeInMillis(milliseconds);
		//Random r = new Random();
		//return cal.get(Calendar.YEAR) + "" + r.nextInt(365);
		return cal.get(Calendar.YEAR) + "" + cal.get(Calendar.DAY_OF_YEAR);
	}

	/*
	 * Returns seconds
	 */
	public static long getNixDate(long milliseconds) {
		Calendar cal1 = Calendar.getInstance();
		cal1.setTimeInMillis(milliseconds);
		
		Calendar cal2 = Calendar.getInstance();
		cal2.set(cal1.get(Calendar.YEAR), cal1.get(Calendar.MONTH), cal1.get(Calendar.DATE), 0, 0, 0);
		
		//apparently the millisecond is still allowed to vary, by dividing by 1000 (not 1000.0), we simply truncate it off
		
		return cal2.getTimeInMillis() / 1000; //in seconds
	}
	
	public static boolean isToday(long seconds){
		Calendar c = Calendar.getInstance();
		
		if(Globals.getNixDate(c.getTimeInMillis()) == seconds){
			return true;
		}
		return false;
	}
	
	public static String getJSDate(long seconds){
		Calendar cal = Calendar.getInstance();
		cal.setTimeInMillis((long) (seconds * 1000.0));
		return cal.get(Calendar.DAY_OF_MONTH) + "" + cal.get(Calendar.MONTH) + "" + cal.get(Calendar.YEAR);
	}

	public static boolean inArray(String needle, String[] haystack) {
		for(int i = 0; i < haystack.length; i++){
			if(needle.equals(haystack[i])){
				return true;
			}
		}
		return false;
	}
}
