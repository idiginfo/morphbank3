//This file contains the annotations report when no scientific  /
//name is specified based on the kingdom rank and dates         /
//                                                              /
//created by: Karolina Maneva-Jakimoska                         /
//date      : April 14 2007                                     /
/////////////////////////////////////////////////////////////////

import java.lang.Object;
import java.io.*;
import java.io.File;
import java.sql.*;
import javax.swing.*;
import java.util.*;
import java.util.Date;
import jxl.*;
import jxl.write.*; 

public class AnnotationsReport{

    private static Connection conn;
    private static BufferedWriter out;
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
    private static AdditionalFunctions func;

    public AnnotationsReport(WritableWorkbook workbook, Statement new_statement, String [] arguments){

	try{
	    statement=new_statement;
	    func = new AdditionalFunctions(statement);
	    System.out.println("func is " + func);
	    copy = workbook;
	    kingdom = arguments[0];
	    hrank = arguments[1];
	    lrank = arguments[2];
	    scientificName = arguments[3];
	    date_from = arguments[4];
	    date_to = arguments[5];
	    user = arguments[6];

	    String temp="";
	    temp = "SELECT distinct Tree.tsn,unit_name1,unit_name2,unit_name3,unit_name4,scientificName,taxon_author_id,parent_tsn," +
		"rank_name,userId,dateCreated,Annotation.comment,`usage`,unaccept_reason FROM Tree,BaseObject,TaxonConcepts," +
		"TaxonUnitTypes,Annotation WHERE Tree.tsn=TaxonConcepts.tsn AND Tree.rank_id=TaxonUnitTypes.rank_id AND " +
                "BaseObject.id=Annotation.id AND " + 
                " Annotation.objectId=TaxonConcepts.id AND Annotation.objectTypeId='Taxon Name' AND ";
	    if(kingdom.compareTo("0")!=0 && kingdom.compareTo("7")!=0)
		temp = temp + " Tree.kingdom_id=" + kingdom + " AND ";
	    if(hrank.compareTo("0")!=0 && hrank.compareTo("7")!=0)
		temp = temp + " Tree.rank_id >=" + hrank + " AND ";
	    if(lrank.compareTo("0")!=0 && lrank.compareTo("7")!=0)
		temp = temp + "Tree.rank_id <=" + lrank + " AND ";
	    if(date_from.compareTo("all")!=0)
		temp = temp + "dateCreated >'" + date_from + "' AND ";
	    if(date_to.compareTo("today")!=0)
		temp = temp + "dateCreated <'" + date_to + "'";
	    else
		temp = temp + "dateCreated < NOW()";
      
	    System.out.println(temp);
	
	    result=statement.executeQuery(temp);
	    metadata=result.getMetaData();
	    int numberOfRows=0;
	    WritableSheet sheet2 = copy.getSheet(1);
	    Label label=null;
	    if(result.last())
		numberOfRows=result.getRow();
	    result.first();
	    if(numberOfRows > 0 && metadata.getColumnCount()>0){
		String [] parent_tsn= new String[numberOfRows];
		String [] annotation = new String[numberOfRows];
		String []userId = new String[numberOfRows]; 
		String []taxonAuthorId = new String[numberOfRows];
		for(int j=0;j<numberOfRows;j++){
		    String tsn = result.getString(1);
		    String scientificName = result.getString(6);
                    taxonAuthorId [j] = result.getString(7);
                    parent_tsn[j] = result.getString(8);
                    String rank_name = result.getString(9);
		    userId [j] = result.getString(10);
                    String dateCreated = result.getString(11);
		    annotation [j] = result.getString(12);
		    String usage = result.getString(13);
		    String unaceptReason = result.getString(14);
		    for(int k=0;k<4;k++){
			if(result.getString(k+2).compareTo("null")!=0){
			    label = new Label(k,j+1,result.getString(k+2));
			    sheet2.addCell(label); 
			}
		    }
		    label = new Label(6,j+1,rank_name);
		    sheet2.addCell(label);
		    //label = new Label(5,j+1,usage);
		    //sheet2.addCell(label);
		    result.next();
		}
		for(int j=0;j<numberOfRows;j++){
		    label = new Label(7,j+1,func.GetParent(parent_tsn[j]));
		    sheet2.addCell(label);
	
                    label = new Label(10,j+1,func.GetSubmitter(userId[j]));
                    sheet2.addCell(label);
        
		    if(taxonAuthorId[j]!="0"){
			label = new Label(4,j+1,func.GetAuthor(taxonAuthorId[j]));
			sheet2.addCell(label);
		    }
		}
		ParseAnnotation(annotation,numberOfRows);
         	FileInFrontPage frontPage = new FileInFrontPage(copy,statement,arguments);
		copy.write();
		copy.close();
	    }
	    else{
		System.out.println("Problems querying the database");
		System.exit(1);
	    }
	}catch(Exception e){
	    e.printStackTrace();
	    System.exit(1);
	}
    }//end of class constructor


    //public method to parse annotation comments
    public static void ParseAnnotation(String []annotations,int rows){

	String inputStr = "";
	String patternStr = "";
	String [] fields=null;
	WritableSheet sheet2 = copy.getSheet(1);
	Label label=null;
	try{
	    for(int i=0;i<rows;i++){
		inputStr = annotations[i];
		patternStr = "<br/>";
		fields = inputStr.split(patternStr,-1);
		String comment="";
		for(int j=0;j<fields.length;j++){
		    if(fields[j].compareTo("")!=0){
			String whole = fields[j].substring(3,fields[j].length());
			//System.out.println("whole string is " + whole);
			String first = whole.substring(0,whole.indexOf("</b>")).trim();
			String second = whole.substring(whole.indexOf("</b>")+4,whole.length()).trim();
			System.out.println("first is " + first + " second is " + second);
		
			if(first.compareTo("Name should be:")==0){
			    label = new Label(9,i+1,second);
			    sheet2.addCell(label);
			    if(kingdom.compareTo("1")==0 || kingdom.compareTo("2")==0 || kingdom.compareTo("5")==0)
                                label = new Label(5,i+1,"invalid");
                            else
                                label = new Label(5,i+1,"not accepted");
			    sheet2.addCell(label);
			    //after the dropdown is add in annotation extract unaccept reason and put in the
			    //excel spreadsheet
			}
			if(first.compareTo("Taxon Author should be:")==0){
			    label = new Label(4,i+1,second);
			    sheet2.addCell(label);
			}
			if(first.compareTo("Should be made synonym of:")==0){
			    if(kingdom.compareTo("1")==0 || kingdom.compareTo("2")==0 || kingdom.compareTo("5")==0)
				label = new Label(5,i+1,"invalid");
			    else
				label = new Label(5,i+1,"not accepted");
			    sheet2.addCell(label);
			    String tsn = second.substring(1,second.indexOf("]")).trim();
			    String name = second.substring(second.indexOf("-")+1,second.length()).trim();
			    name = name + " " + func.GetAuthorFromTSN(tsn);
			    label = new Label(9,i+1,name);
			    sheet2.addCell(label);
			    label = new Label(8,i+1,"synonym");
			    sheet2.addCell(label); 
			}
			if(first.compareTo("Should be removed from synonymy!Reasons:")==0){
			    if(kingdom.compareTo("1")==0 || kingdom.compareTo("2")==0 || kingdom.compareTo("5")==0)
                                label = new Label(5,i+1,"valid");
                            else
                                label = new Label(5,i+1,"accepted");
			    comment = second;
			}
			if(first.compareTo("Should be child of:")==0){
			    if(kingdom.compareTo("1")==0 || kingdom.compareTo("2")==0 || kingdom.compareTo("5")==0)
                                label = new Label(5,i+1,"invalid");
                            else
                                label = new Label(5,i+1,"not accepted");
                            sheet2.addCell(label);
                            String tsn = second.substring(1,second.indexOf("]")).trim();
                            String name = second.substring(second.indexOf("-")+1,second.length()).trim();
                            name = name + " " + func.GetAuthorFromTSN(tsn);
                            label = new Label(7,i+1,name);
                            sheet2.addCell(label);
			}
			if(first.compareTo("Unaccepted reasons are:")==0){
			    label = new Label(8,i+1,second);
			    sheet2.addCell(label);
			}
			if(first.compareTo("Additional comments:")==0){
			    comment = comment + second ;
			}
			if(first.compareTo("Reference should be:")==0){
			    String referenceId = second.substring(1,second.indexOf("]"));
			    WritePublication(referenceId,i);
			}
		
			label = new Label(11,i+1,comment);
			sheet2.addCell(label);
		    }
		    else
			continue;
		}
	    }
	}catch(WriteException we){
	    we.printStackTrace();
            System.exit(1);
	}
    }//end of public ParseAnnotation

    //public method to extract and write the Publication information
    public static void WritePublication(String publication, int row){

	WritableSheet sheet2 = copy.getSheet(1);

	try{
	    String query = "SELECT address,author,publicationTitle,chapter,edition,editor,month,day,note,number,publisher,series," +
		"title,volume,year,isbn,issn,pages FROM Publication WHERE id=" + publication;
	    System.out.println(query);
	    
	    ResultSet result_query=statement.executeQuery(query);
	    ResultSetMetaData metadata_query=result_query.getMetaData();
	    int numberOfRows=0;
	    if(result_query.last())
		numberOfRows=result_query.getRow();
	    result_query.first();
	    if(numberOfRows>0 && metadata_query.getColumnCount()>0){
		String aut_edi = result_query.getString(2);
		System.out.println("autor is " + aut_edi);
		if(result_query.getString(6).compareTo("")!=0)
		    aut_edi = aut_edi + " / " + result_query.getString(6) + ",eds.";
		Label label = new Label(12,row+1,aut_edi);
		sheet2.addCell(label);
		label = new Label(13,row+1,result_query.getString(13));
		sheet2.addCell(label);
		String publication_name = result_query.getString(3);
		if(result_query.getString(12).compareTo("")!=0)
		    publication_name = publication_name + ", ser. " + result_query.getString(12);
		if(result_query.getString(14).compareTo("0")!=0)
		    publication_name = publication_name + ", vol. " + result_query.getString(14);
		if(result_query.getString(10).compareTo("0")!=0)
		    publication_name = publication_name + ",no. " + result_query.getString(10);
		label = new Label(14,row+1,publication_name);
		sheet2.addCell(label);
		label = new Label(17,row+1,result_query.getString(11));
		sheet2.addCell(label);
		label = new Label(18,row+1,result_query.getString(1));
		sheet2.addCell(label);
		label = new Label(19,row+1,result_query.getString(18));
		sheet2.addCell(label);
		label = new Label(20,row+1,result_query.getString(9));
		sheet2.addCell(label);
		label = new Label(21,row+1,result_query.getString(16));
		sheet2.addCell(label);
		label = new Label(22,row+1,result_query.getString(17));
		sheet2.addCell(label);
		String date = result_query.getString(15);
		String month = result_query.getString(7);
		if(month.compareTo("")==0){
		    month = "Jan";
		    System.out.println("month is " + month);
		}
		month = func.ConvertMonth(month);
		date = month + "/" + date;
		String day = "1";
		if(result_query.getString(8).compareTo("0")!=0)
		    day = result_query.getString(8);
		if(day.length()==1)
		    day = "0" + day;
		date = day + "/" + date;
		label = new Label(15,row+1,date);
		sheet2.addCell(label);
		label = new Label(16,row+1,date);
		sheet2.addCell(label);
		
	    }
	}catch(Exception e){
	    e.printStackTrace();
	    System.exit(1);
	}
    }//end of WritePublication

}//end of class NamesReport
