//This file makes the connection to the data base      //
//It loads the driver and using username and password  //
//connects with the data base.The credentials are      //
//obtained through GetCredentials class                // 
//                                                     //
//created by: Karolina Maneva-Jakimoska                //
//date:       January 18 2006                          //
/////////////////////////////////////////////////////////

import java.sql.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.*;


public class GetConnection {
    
    private Connection conn=null;
    public GetConnection(){
	try{
	   
      	    Class.forName("com.mysql.jdbc.Driver").newInstance();
	    System.out.println("The Driver has been loaded");

	    //obtaining credential from authorised user
	    conn = DriverManager.getConnection("jdbc:mysql://localhost/MB27","webuser","namaste");
	    System.out.println("Connection was established");
	}
	catch(Exception e){
	    e.printStackTrace();
	    System.exit(1);
	}
	
    }//end of GetConnection constructor


    //public method that provides access to the connection
    public Connection getConnect(){
	return conn;
    }//end of getConnect()

}//end of class GetConnection
