//This file contains the mainClass to read from the database and/
//write into excel file report                                  /
//                                                              /
//created by: Karolina Maneva-Jakimoska                         /
//date      : April 04 2007                                     /
/////////////////////////////////////////////////////////////////


import java.io.*;
import java.io.File;
import java.sql.*;
import javax.swing.*;
import java.util.*;
import java.util.Date;
import jxl.*;
import jxl.write.*; 

public class CreateReport{

    private static Connection conn;
    private static String reportType;
    private static String kingdom;
    private static String hrank;
    private static String lrank;
    private static String scientificName;
    private static String date_from;
    private static String date_to;
    private static String user;
    private static ResultSet result;
    private static Statement statement;
    private static ResultSetMetaData metadata;
    private static Workbook workbook;
    private static WritableWorkbook copy;


    public static void main(String args[]){

	try{
	    System.out.println("Before conection");
	    GetConnection newconnect=new GetConnection();
	    conn=newconnect.getConnect();
	    statement=conn.createStatement();
	    NamesReport namesReport;
	    SingleNameReport oneNameReport;
	    AnnotationsReport annotationReport;
	    SingleNameAnnotations oneNameAnnotation;

	    reportType = args[0];
	    kingdom = args[1];
	    hrank = args[2];
	    lrank = args[3];
	    scientificName = args[4];
	    date_from = args[5];
	    date_to = args[6];
	    user = args[7];
	    String [] arguments = new String[7];
	    for(int k=0; k<7;k++){
		arguments[k] = args[k+1];
	    }
	    if(reportType.compareTo("1")==0){
		workbook = Workbook.getWorkbook(new File("NewNames.xls"));
		copy = Workbook.createWorkbook(new File("/data/www/reports/output.xls"), workbook);
		if(scientificName.compareTo("Not_specified")==0)
		    namesReport = new NamesReport(copy,statement,arguments);
		else
		    oneNameReport = new SingleNameReport(copy,statement,arguments);
	    }
	    else{
		workbook = Workbook.getWorkbook(new File("Corrections.xls"));
                copy = Workbook.createWorkbook(new File("/data/www/reports/output.xls"), workbook);
		if(scientificName.compareTo("Not_specified")==0)
                    annotationReport = new AnnotationsReport(copy,statement,arguments);
                else
		    oneNameAnnotation = new SingleNameAnnotations(copy,statement,arguments);
	    }
	}
	catch(Exception e){
	    e.printStackTrace();
	    System.exit(1);
	}
	finally{
	    try{
		conn.close();
		System.exit(0);
	    }
	    catch(Exception e){
		e.printStackTrace();
		System.exit(1);
	    }
	}
    }//end of main
}//end of class CreateReport
