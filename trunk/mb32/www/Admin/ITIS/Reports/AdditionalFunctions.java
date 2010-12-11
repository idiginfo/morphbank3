//This class contains functions used from /
//every other class in this package       /
//                                        /
//Author: Karolina Maneva-Jakimoska       /
//date:   April 17th 2007                 /
//////////////////////////////////////////


import java.io.*;
import java.io.File;
import java.sql.*;
import javax.swing.*;
import java.util.*;
import java.util.Date;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import jxl.*;
import jxl.write.*;

public class AdditionalFunctions{

    private static Statement statement;

    public AdditionalFunctions(Statement new_statement){
	statement = new_statement;
    }

//public method that return the parent
public static String GetParent(String parent_id){
    String parent="", taxon_author_id="";
    String query = "SELECT scientificName,taxon_author_id FROM Tree WHERE tsn=" + parent_id;
    System.out.println(query);
    try{
	ResultSet result_query=statement.executeQuery(query);
	ResultSetMetaData metadata_query=result_query.getMetaData();
	int numberOfRows=0;
	if(result_query.last())
	    numberOfRows=result_query.getRow();
	result_query.first();
	if(numberOfRows>0 && metadata_query.getColumnCount()>0){
	    parent = result_query.getString(1).trim();
	    taxon_author_id = result_query.getString(2).trim();
	    if(taxon_author_id.compareTo("0")!=0 && taxon_author_id.compareTo("NULL")!=0){
		query = "SELECT taxon_author FROM TaxonAuthors WHERE taxon_author_id=" + taxon_author_id;
		result_query=statement.executeQuery(query);
		metadata_query=result_query.getMetaData();
		numberOfRows=0;
		if(result_query.last())
		    numberOfRows=result_query.getRow();
		result_query.first();
		if(numberOfRows>0 && metadata_query.getColumnCount()>0)
		    parent = parent + " " + result_query.getString(1).trim();
	    }
	}
    }catch(SQLException sql){
	sql.printStackTrace();
	System.exit(1);
    }
    return parent;
}

    //public method that returns the name of the submitter
    public static String GetSubmitter(String userId){
        String name="";
        String query = "SELECT name, email FROM User WHERE id=" + userId;
        System.out.println(query);
        try{
            ResultSet result_query=statement.executeQuery(query);
            ResultSetMetaData metadata_query=result_query.getMetaData();
            int numberOfRows=0;
            if(result_query.last())
                numberOfRows=result_query.getRow();
            result_query.first();
            if(numberOfRows > 0 && metadata_query.getColumnCount() > 0)
                name=result_query.getString(1);
            name = name + "," + result_query.getString(2);
        }catch(SQLException sql){
            sql.printStackTrace();
            System.exit(1);
        }
        return name;
    }

    //public method that returns the name of the author
    public static String GetAuthor(String authorId){
        String name="";
        String query = "SELECT taxon_author FROM TaxonAuthors WHERE taxon_author_id=" + authorId;
        //System.out.println(query);
        try{
            ResultSet result_query=statement.executeQuery(query);
            ResultSetMetaData metadata_query=result_query.getMetaData();
            int numberOfRows=0;
            if(result_query.last())
                numberOfRows=result_query.getRow();
            result_query.first();
            if(numberOfRows>0 && metadata_query.getColumnCount()>0)
                name=result_query.getString(1);
        }catch(SQLException sql){
            sql.printStackTrace();
            System.exit(1);
        }
        return name;
    }

    //public method that returns author name from tsn
    public static String GetAuthorFromTSN(String tsn){
	String name="";
	int taxon_author_id=0;
	String query = "SELECT taxon_author_id FROM Tree where tsn=" + tsn;
	try{
            ResultSet result_query=statement.executeQuery(query);
            ResultSetMetaData metadata_query=result_query.getMetaData();
            int numberOfRows=0;
            if(result_query.last())
                numberOfRows=result_query.getRow();
            result_query.first();
            if(numberOfRows>0 && metadata_query.getColumnCount()>0){
                taxon_author_id=result_query.getInt(1);
		if(taxon_author_id!=0){
		    query = "SELECT taxon_author FROM TaxonAuthors where taxon_author_id=" + taxon_author_id;
		    result_query=statement.executeQuery(query);
		    metadata_query=result_query.getMetaData();
		    numberOfRows=0;
		    if(result_query.last())
			numberOfRows=result_query.getRow();
		    result_query.first();
		    if(numberOfRows>0 && metadata_query.getColumnCount()>0){
			name = result_query.getString(1);
		    }
		}
	    }
        }catch(SQLException sql){
            sql.printStackTrace();
            System.exit(1);
        }
        return name;

    }//end of GetAuthorFromTSN

    //this function converts the month in a number format
    public String ConvertMonth(Object month){

	String num_month="";
	if(month.equals("Jan"))
	    num_month="01";
	else if(month.equals("Feb"))
	    num_month="02";
	else if(month.equals("Mar"))
	    num_month="03";
	else if(month.equals("Apr"))
	    num_month="04";
	else if(month.equals("May"))
	    num_month="05";
	else if(month.equals("Jun"))
	    num_month="06";
	else if(month.equals("Jul"))
	    num_month="07";
	else if(month.equals("Aug"))
	    num_month="08";
	else if(month.equals("Sep"))
	    num_month="09";
	else if(month.equals("Oct"))
	    num_month="10";
	else if(month.equals("Nov"))
	    num_month="11";
	else 
	    num_month="12";
	return num_month;

    }//end of method ConvertMonth

}//end of public class AdditionalFunctions
