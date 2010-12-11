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

public class FileInFrontPage{

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


    public FileInFrontPage(WritableWorkbook workbook,Statement new_statement, String [] arguments){

	try{
	    copy = workbook;
	    statement=new_statement;

	    kingdom = arguments[0];
	    hrank = arguments[1];
	    lrank = arguments[2];
	    scientificName = arguments[3];
	    date_from = arguments[4];
	    date_to = arguments[5];
	    user = arguments[6];
	    FiledInFrontPage();
	}
	catch(Exception e){
	    e.printStackTrace();
	}
    }//end of the constructor

    //public method that populates the front page of the report
    public static void FiledInFrontPage(){

	try{
	    WritableSheet sheet1 = copy.getSheet(0);
	    Label label=null;
	    String current_date = GetDate();
	    label = new Label(1,3,current_date);
	    sheet1.addCell(label);
	    label = new Label(1,4,user);
	    sheet1.addCell(label);
	    String kingdom_name="";
	    if(kingdom.compareTo("0")==0 || kingdom.compareTo("7")==0)
		kingdom_name = "All kingdoms";
	    else
		kingdom_name = GetKingdomName(kingdom);
	    label = new Label(1,5,kingdom_name);
	    sheet1.addCell(label);
	    if(scientificName.compareTo("noname")==0)
		scientificName="Not specified";
	    label = new Label(1,6,scientificName);
	    sheet1.addCell(label);
	    String rank_name="";
	    if(hrank.compareTo("0")==0 || hrank.compareTo("7")==0)
                rank_name = "All ranks";
            else
                rank_name = GetRankName(hrank);
	    label = new Label(1,7,rank_name);
	    sheet1.addCell(label);
	    if(lrank.compareTo("0")==0 || lrank.compareTo("7")==0)
                rank_name = "All ranks";
            else
                rank_name = GetRankName(lrank);
	    label = new Label(1,8,rank_name);
	    sheet1.addCell(label);
	    if(date_from.compareTo("all")==0)
		date_from = "All";
	    if(date_to.compareTo("today")==0)
		date_to = GetDate();
	    label = new Label(1,9,date_from + " to " + date_to);
	    sheet1.addCell(label);
	}catch(Exception e){
	    e.printStackTrace();
            System.exit(1);
	}

    }//end of function FiledInFrontPage

    //public method to retreive kingdom name
    public static String GetKingdomName(String kingdom_id){
        String kingdom="";
        String query = "SELECT kingdom_name FROM Kingdoms WHERE kingdom_id=" + kingdom_id;
        //System.out.println(query);
        try{
            ResultSet result_query=statement.executeQuery(query);
            ResultSetMetaData metadata_query=result_query.getMetaData();
            int numberOfRows=0;
            if(result_query.last())
                numberOfRows=result_query.getRow();
            result_query.first();
            if(numberOfRows>0 && metadata_query.getColumnCount()>0)
                kingdom=result_query.getString(1);
        }catch(SQLException sql){
            sql.printStackTrace();
            System.exit(1);
        }
        return kingdom;
    }//end og GetKingdomName

    //public method to get rank name
    public static String GetRankName(String rank_id){
        String rank="";
        String query = "SELECT rank_name FROM TaxonUnitTypes WHERE rank_id=" + rank_id;
        //System.out.println(query);
        try{
            ResultSet result_query=statement.executeQuery(query);
            ResultSetMetaData metadata_query=result_query.getMetaData();
            int numberOfRows=0;
            if(result_query.last())
                numberOfRows=result_query.getRow();
            result_query.first();
            if(numberOfRows>0 && metadata_query.getColumnCount()>0)
                rank=result_query.getString(1);
        }catch(SQLException sql){
            sql.printStackTrace();
            System.exit(1);
        }
        return rank;
    }//end of class GetRankName

    //public method to get current date
    public static String GetDate(){
        String date="";
	String query="SELECT NOW()";
	try{
	    ResultSet result_query=statement.executeQuery(query);
	    ResultSetMetaData metadata_query=result_query.getMetaData();
	    int numberOfRows=0;
	    if(result_query.last())
		numberOfRows=result_query.getRow();
	    if(numberOfRows!=0 && metadata_query.getColumnCount()==1){
		result_query.first();
		date=result_query.getString(1).trim();
		date=date.substring(0,10); 
	    }
	    else{
		System.out.println("Problems retreiving current date");
		System.exit(1);
	    }
	}catch(SQLException sql){
	    sql.printStackTrace();
	    System.exit(1);
	}
    
	return date;

    }//end of method GetDate

}//end of class FileInFrontPage
