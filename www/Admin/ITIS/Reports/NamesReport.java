//This file creates the report when no scientificName is        /
//speciefied based on the kingdom, ranks and dates selected     /
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

public class NamesReport{

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
    private static AdditionalFunctions func;

    public NamesReport(WritableWorkbook workbook, Statement new_statement, String [] arguments){

	try{
	    statement=new_statement;
	    func = new AdditionalFunctions(statement);
	    copy = workbook;
	    kingdom = arguments[0];
	    hrank = arguments[1];
	    lrank = arguments[2];
	    scientificName = arguments[3];
	    date_from = arguments[4];
	    date_to = arguments[5];
	    user = arguments[6];

	    String temp="";
	    temp = "SELECT distinct Tree.tsn,unit_name1,unit_name2,unit_name3,unit_name4,scientificName,taxon_author,parent_tsn," +
		"rank_name, pages, publicationId,userId dateCreated FROM Tree,BaseObject,TaxonConcepts, TaxonAuthors,TaxonUnitTypes " + 
		"WHERE Tree.tsn=TaxonConcepts.tsn AND Tree.rank_id=TaxonUnitTypes.rank_id AND " +
                "BaseObject.id=TaxonConcepts.id AND TaxonAuthors.taxon_author_id=Tree.taxon_author_id AND " + 
		" Tree.tsn>=999000000 AND `usage`='public' AND nameType='Regular scientific name' AND publicationId!='' AND ";
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
		String []parent_tsn= new String[numberOfRows];
		String []publicationId = new String[numberOfRows];
		String []userId = new String[numberOfRows]; 
		for(int j=0;j<numberOfRows;j++){
		    String tsn = result.getString(1);
		    String scientificName = result.getString(6);
                    String taxon_author = result.getString(7);
                    parent_tsn[j] = result.getString(8);
                    String rank_name = result.getString(9);
                    publicationId[j] = result.getString(11);
                    userId[j] = result.getString(12);
		    for(int k=0;k<4;k++){
			if(result.getString(k+2).compareTo("null")!=0){
			    label = new Label(k,j+1,result.getString(k+2));
			    sheet2.addCell(label); 
			}
		    }
		    label = new Label(4,j+1,taxon_author);
		    sheet2.addCell(label);
		    label = new Label(5,j+1,rank_name);
		    sheet2.addCell(label);
		    result.next();
		}
		for(int j=0;j<numberOfRows;j++){
		    System.out.println(parent_tsn[j]);
		    label = new Label(6,j+1,func.GetParent(parent_tsn[j]));
		    sheet2.addCell(label);
		}
		for(int j=0;j<numberOfRows;j++){
                    label = new Label(18,j+1,func.GetSubmitter(userId[j]));
                    sheet2.addCell(label);
                }

                WritePublication(publicationId,numberOfRows);
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


    //public method to extract and write the Publication information
    public static void WritePublication(String [] publications, int rows){

	WritableSheet sheet2 = copy.getSheet(1);

	try{
	    for(int j=0;j<rows;j++){
		String query = "SELECT address,author,publicationTitle,chapter,edition,editor,month,day,note,number,publisher,series,title," +
		    "volume,year,isbn,issn,pages FROM Publication WHERE id=" + publications[j];
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
		    Label label = new Label(7,j+1,aut_edi);
                    sheet2.addCell(label);
		    label = new Label(8,j+1,result_query.getString(13));
		    sheet2.addCell(label);
		    String publication_name = result_query.getString(3);
		    if(result_query.getString(12).compareTo("")!=0)
			publication_name = publication_name + ", ser. " + result_query.getString(12);
		    if(result_query.getString(14).compareTo("0")!=0)
			publication_name = publication_name + ", vol. " + result_query.getString(14);
		    if(result_query.getString(10).compareTo("0")!=0)
			publication_name = publication_name + ",no. " + result_query.getString(10);
		    label = new Label(9,j+1,publication_name);
		    sheet2.addCell(label);
		    label = new Label(12,j+1,result_query.getString(11));
		    sheet2.addCell(label);
		    label = new Label(13,j+1,result_query.getString(1));
                    sheet2.addCell(label);
		    label = new Label(15,j+1,result_query.getString(9));
                    sheet2.addCell(label);
		    label = new Label(16,j+1,result_query.getString(16));
                    sheet2.addCell(label);
		    label = new Label(17,j+1,result_query.getString(17));
                    sheet2.addCell(label);
		    label = new Label(14,j+1,result_query.getString(18));
                    sheet2.addCell(label);
		    String date = result_query.getString(15);
		    String month = "Jan";
		    System.out.println("month is " + result_query.getString(7));
		    if(result_query.getString(7).compareTo("")!=0)
			month = result_query.getString(7);
		    month = func.ConvertMonth(month);
		    date = month + "/" + date;
		    String day = "1";
		    if(result_query.getString(8).compareTo("0")!=0)
			day = result_query.getString(8);
		    //System.out.println("day length is " + day);
		    if(day.length()==1)
			day = "0" + day;
		    date = day + "/" + date;
		    label = new Label(10,j+1,date);
		    sheet2.addCell(label);
		    label = new Label(11,j+1,date);
                    sheet2.addCell(label);
		}
	    }
	}catch(Exception e){
	    e.printStackTrace();
	    System.exit(1);
	}
    }//end of WritePublication

}//end of class NamesReport
