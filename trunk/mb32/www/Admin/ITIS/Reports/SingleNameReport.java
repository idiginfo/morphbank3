
//This file contains creates report for specific scientificName /
//group (eg:family, genus ect.)                                 /
//                                                              /
//created by: Karolina Maneva-Jakimoska                         /
//date      : April 09 2007                                     /
/////////////////////////////////////////////////////////////////


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


public class SingleNameReport{

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
    private static int row;

    public SingleNameReport(WritableWorkbook workbook, Statement new_statement, String [] arguments){

	String main_tsn = "";
	String main_rank = "";
	String main_kingdom = "";
	row=0;
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
	    temp = "SELECT tsn,rank_id,kingdom_id from Tree where scientificName='" + scientificName + "'";
	    System.out.println(temp);
            result=statement.executeQuery(temp);
            metadata=result.getMetaData();
            int numberOfRows=0;
	    int hrankVal=Integer.parseInt(hrank);
	    int main_rankVal=0;
            if(result.last())
                numberOfRows=result.getRow();
            result.first();
            if(numberOfRows > 0 && metadata.getColumnCount()>0){
		main_tsn = result.getString(1);
                main_rank= result.getString(2);
		main_rankVal = Integer.parseInt(main_rank);
		main_kingdom = result.getString(3);
	    }
	    if(HasChildren(main_tsn)==true)
		FindChildInformation(main_tsn,arguments,func);

	    FileInFrontPage frontPage = new FileInFrontPage(copy,statement,arguments);
	    copy.write();
	    copy.close();
	}catch(Exception e){
            e.printStackTrace();
            System.exit(1);
	}
    }//end of constructor
    
    //public method that checks for existance of children
    public boolean HasChildren(String tsn){ 

	String temp = "SELECT tsn from Tree where parent_tsn='" + tsn + "'";
	if(lrank.compareTo("0")!=0 && lrank.compareTo("7")!=0)
	    temp = temp + " AND rank_id<=" + lrank;
	System.out.println(temp);
	int numberOfRows=0;
	boolean flag = false;
	try{
	    result=statement.executeQuery(temp);
	    metadata=result.getMetaData();
	    if(result.last())
		numberOfRows=result.getRow();
	    result.first();
	    if(numberOfRows > 0 && metadata.getColumnCount()>0)
		flag = true;
	}catch(SQLException sql){
	    sql.printStackTrace();
	    System.exit(1);
	}
	    return flag;

    }//end of method HasChildren
    
    //public method to find child information
    public void FindChildInformation(String tsn, String [] arguments, AdditionalFunctions func){

	WritableSheet sheet2 = copy.getSheet(1);
	Label label=null;
	String children[] = null;
	String temp="",current_tsn="", dateCreated="";
	int dateCheck=1;
	temp = "SELECT distinct tsn from Tree where parent_tsn='" + tsn + "'";
	try{
	    result=statement.executeQuery(temp);
            metadata=result.getMetaData();
            int numberOfRows=0;
            if(result.last())
                numberOfRows=result.getRow();
            if(numberOfRows > 0 && metadata.getColumnCount()>0){
		result.first();
		children = new String[numberOfRows];
		for(int j=0;j<numberOfRows;j++){
		    children[j] = result.getString(1);
		    result.next();
                }
	    }
	    for(int j=0;j<numberOfRows;j++){
		int current_tsnId = Integer.parseInt(children[j]);
		if(current_tsnId>=999000000){
		    temp = "SELECT distinct Tree.tsn,unit_name1,unit_name2,unit_name3,unit_name4,scientificName,`usage`," +
			"taxon_author,parent_tsn,rank_name, pages, publicationId, userId, dateCreated, nameType FROM Tree," +
			" BaseObject, TaxonConcepts, TaxonAuthors,TaxonUnitTypes" +
			" WHERE Tree.tsn=TaxonConcepts.tsn AND Tree.tsn='" + current_tsnId + "' AND " +
			"BaseObject.id=TaxonConcepts.id AND TaxonAuthors.taxon_author_id=Tree.taxon_author_id AND " + 
                        "Tree.rank_id=TaxonUnitTypes.rank_id";
		    System.out.println(temp);
       
		    result=statement.executeQuery(temp);
		    metadata=result.getMetaData();
		    int RowNumber=0;
		    if(result.last())
			RowNumber=result.getRow();
		    if(RowNumber > 0 && metadata.getColumnCount()>0){
			current_tsn = result.getString(1);
			dateCreated = result.getString(14);
			DateFormat df = new SimpleDateFormat("yyyy-MM-dd");
			Date d1=df.parse(dateCreated);
			Date d2=null;
			if(date_from.compareTo("all")!=0){
			    d2 = df.parse(date_from);
			    if(d2.before(d1) || d2.equals(d1)) 
				dateCheck = 1;
			    else
				dateCheck = 0;
			}
			if(date_to.compareTo("today")!=0){
			    d2 = df.parse(date_to);
			    if(d1.before(d2) || d1.equals(d2))
				dateCheck = 1;
			    else
				dateCheck = 0;
			}
			if(result.getString(7).compareTo("public")==0 && result.getString(15).compareTo("Regular scientific name")==0 && result.getString(12).compareTo("")!=0 && dateCheck==1){

			    String scientificName = result.getString(6);
			    String taxon_author = result.getString(8);
			    String publicationId = result.getString(12);
			    for(int k=0;k<4;k++){
				if(result.getString(k+2).compareTo("null")!=0){
				    label = new Label(k,row+1,result.getString(k+2));
				    sheet2.addCell(label); 
				}
			    }
			    label = new Label(4,row+1,taxon_author);
			    sheet2.addCell(label);
			    label = new Label(5,row+1,result.getString(10));
			    sheet2.addCell(label);
			
			    String parent_tsn = result.getString(9);
			    String userId = result.getString(13);
			    WritePublication(publicationId,row+1);
			    label = new Label(18,row+1,func.GetSubmitter(userId));
			    sheet2.addCell(label);
			    label = new Label(6,row+1,func.GetParent(parent_tsn));
			    sheet2.addCell(label);


			row= row+1;
			}
		    }
		}
		    continue;
	    }
	    for(int j=0;j<numberOfRows;j++){
		if(HasChildren(children[j])==true)
		    FindChildInformation(children[j],arguments,func);
		else
		    continue;
	    }
	}catch(Exception e){
	    e.printStackTrace();
	    System.exit(1);
	}
    }//end of FindChildInformation

    //public method to extract and write the Publication information
    public static void WritePublication(String publicationId,int rowNum){

	WritableSheet sheet2 = copy.getSheet(1);

	try{
		String query = "SELECT address,author,publicationTitle,chapter,edition,editor,month,day,note,number,publisher," +
                               "series,title,volume,year,isbn,issn,pages FROM Publication WHERE id=" + publicationId;
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
		    Label label = new Label(7,rowNum,aut_edi);
                    sheet2.addCell(label);
		    label = new Label(8,rowNum,result_query.getString(13));
		    sheet2.addCell(label);
		    String publication_name = result_query.getString(3);
		    if(result_query.getString(12).compareTo("")!=0)
			publication_name = publication_name + ", ser. " + result_query.getString(12);
		    if(result_query.getString(14).compareTo("0")!=0)
			publication_name = publication_name + ", vol. " + result_query.getString(14);
		    if(result_query.getString(10).compareTo("0")!=0)
			publication_name = publication_name + ",no. " + result_query.getString(10);
		    label = new Label(9,rowNum,publication_name);
		    sheet2.addCell(label);
		    label = new Label(12,rowNum,result_query.getString(11));
		    sheet2.addCell(label);
		    label = new Label(13,rowNum,result_query.getString(1));
                    sheet2.addCell(label);
		    label = new Label(15,rowNum,result_query.getString(9));
                    sheet2.addCell(label);
		    label = new Label(16,rowNum,result_query.getString(16));
                    sheet2.addCell(label);
		    label = new Label(17,rowNum,result_query.getString(17));
                    sheet2.addCell(label);
		    label = new Label(14,rowNum,result_query.getString(18));
                    sheet2.addCell(label);

		    String date = result_query.getString(15);
		    String month = "Jan";
		    System.out.println("month is " + result_query.getString(7));
		    if(result_query.getString(7).compareTo("")!=0)
			month = result_query.getString(7);
		    month = func.ConvertMonth(month);
		    date = month + "/" + date;
		    String day = "01";
		    if(result_query.getString(8).compareTo("0")!=0)
			day = result_query.getString(8);
		    if(day.length()==1)
			day = "0" + day;
		    date = day + "/" + date;
		    label = new Label(10,rowNum,date);
		    sheet2.addCell(label);
		    label = new Label(11,rowNum,date);
                    sheet2.addCell(label);
		}
	}catch(Exception e){
	    e.printStackTrace();
	    System.exit(1);
	}
    }//end of WritePublication

}//end of class NamesReport
