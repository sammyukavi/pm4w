package com.sukavi.pm4w;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.sukavi.pm4w.config.Config;
import com.sukavi.pm4w.http.JSONParser;

import android.app.IntentService;
import android.content.Intent;
import android.os.Environment;
import android.os.StatFs;
import android.widget.Toast;

public class DownloadService extends IntentService{

    public static final String NOTIFICATION = "com.sukavi.pm4w";
    public static final String RESULT = "result";
    public static final String NAME = "name";

    public DownloadService() {
	super("DownloadService");
    }

    @Override
    protected void onHandleIntent(Intent intent) {

	JSONParser jsonp = new JSONParser();
	int server_status;
	int request_status;	
	int resultCode =  android.app.Activity.RESULT_CANCELED;
	String name=null;


	List<NameValuePair> params = new ArrayList<NameValuePair>();	  
	String api_url = "?a=check-update";

	JSONObject json = jsonp.makeHttpRequest(api_url, "POST", params);


	try {
	    JSONObject server_info = json.getJSONObject("server_info");
	    server_status = server_info.getInt("server_status");
	    JSONObject data = json.getJSONObject("data");	
	    request_status = data.getInt("request_status");	

	    if(server_status==Config.SERVER_OFFLINE){

	    }else if(server_status==Config.SERVER_UPGRADE){

	    }else{

		if(request_status==Config.REQUEST_SUCCESSFUL){
		    JSONArray updates=data.getJSONArray("updates");

		    final JSONObject latest_update= updates.getJSONObject((updates.length()-1));

		    if(latest_update.length()>0) {
			if(latest_update.getDouble("version")>Double.parseDouble(Config.APP_VERSION)) {			    
			    if(android.os.Environment.getExternalStorageState().equals(android.os.Environment.MEDIA_MOUNTED)) {
				File extdir = Environment.getExternalStorageDirectory();
				StatFs stats = new StatFs(extdir.getAbsolutePath());
				int availableBytes = stats.getAvailableBlocks() * stats.getBlockSize();
				if(availableBytes<latest_update.getInt("size")) {
				    Toast.makeText(getApplicationContext(), "An update od this app is available for download but you require free space in your memory card to update.", Toast.LENGTH_LONG).show();
				}else {
				    try {
					URL url = new URL(latest_update.getString("url"));
					name = latest_update.getString("name");
					String PATH = Environment.getExternalStorageDirectory() + "/pm4w/";
					
					File chk = new File(PATH+name);
					if(chk.exists()) {
					    chk.delete();  
					}
					
					HttpURLConnection c = (HttpURLConnection) url.openConnection();
					c.setRequestMethod("GET");
					c.setDoOutput(true);
					c.connect();

					
					File file = new File(PATH);
					file.mkdirs();
					File outputFile = new File(file, name);
					FileOutputStream fos = new FileOutputStream(outputFile);

					InputStream is = c.getInputStream();

					byte[] buffer = new byte[1024];
					int len1 = 0;
					while ((len1 = is.read(buffer)) != -1) {
					    fos.write(buffer, 0, len1);
					}
					fos.close();
					is.close();
					resultCode =  android.app.Activity.RESULT_OK;


				    } catch (IOException e) {
					Toast.makeText(getApplicationContext(), "Update error! "+e.toString(), Toast.LENGTH_LONG).show();
				    }
				}

			    }else {
				Toast.makeText(getApplicationContext(), "An update od this app is available for download but you require a memory card to update this app", Toast.LENGTH_LONG).show();
			    }


			}
		    }
		}
	    }
	} catch (JSONException e) {
	   e.printStackTrace();
	}



	publishResults(resultCode,name);

    }

    private void publishResults(int result,String name) {
	Intent intent = new Intent(NOTIFICATION);	   
	intent.putExtra(RESULT, result);
	intent.putExtra(NAME, name);
	sendBroadcast(intent);
    }

}