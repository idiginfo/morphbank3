//                                                                /
//This file creates annotation report for specific scientificName /
//group (ex:family, genus ect.)                                   /
//                                                                /
//created by: Karolina Maneva-Jakimoska                           /
//date      : April 14 2007                                       /
///////////////////////////////////////////////////////////////////


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

public class SingleNameAnnotations{

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

    public SingleNameAnnotations(WritableWorkbook workbook, Statement new_statement, String [] arguments){

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

	    if(main_kingdom.compareTo(kingdom)!=0){ //report error
		System.out.println("No scientificName as specified in the choosen kingdom");
		System.exit(1);
	    }

	    if(main_rankVal>hrankVal && hrank.compareTo("0")!=0 && hrank.compareTo("7")!=0){ //report error
		System.out.println("The highest rank choosen is higher that the rank of the name");
		System.exit(1);
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
	String temp="";
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
		    System.out.println("children are " + children[j]);
		    result.next();
                }
	    }
	    for(int j=0;j<numberOfRows;j++){
		temp = "SELECT distinct Tree.tsn,unit_name1,unit_name2,unit_name3,unit_name4,scientificName,taxon_author_id,parent_tsn," +
		    "rank_name,userId,dateCreated,Annotation.comment,`usage`,unaccept_reason FROM Tree,BaseObject,TaxonConcepts," +
		    "TaxonUnitTypes,Annotation WHERE Tree.tsn=TaxonConcepts.tsn AND Tree.rank_id=TaxonUnitTypes.rank_id AND " +
		    "BaseObject.id=Annotation.id AND Tree.tsn=" + children[j] +
		    " AND Annotation.objectId=TaxonConcepts.id AND Annotation.objectTypeId='Taxon Name'";

		    System.out.println(temp);
       		    result=statement.executeQuery(temp);
		    metadata=result.getMetaData();
		    int RowNumber=0;
		    if(result.last())
			RowNumber=result.getRow();
		    String []current_tsn = new String [RowNumber];
		    String []unit_name1 = new String[RowNumber];
		    String []unit_name2 = new String[RowNumber];
		    String []unit_name3 = new String[RowNumber];
		    String []unit_name4 = new String[RowNumber];
		    String []dateCreated = new String [RowNumber]; 
		    String []scientificName = new String [RowNumber];
		    String []taxonAuthorId = new String [RowNumber];
		    String []parent_tsn = new String [RowNumber];
		    String []rank_name = new String [RowNumber];
		    String []userId = new String [RowNumber];
		    String []annotation = new String[RowNumber];
		    String []usage = new String[RowNumber];
		    String []unaceptReason = new String[RowNumber];
		    System.out.println("number of annotations is " + RowNumber);
		    if(RowNumber > 0 && metadata.getColumnCount()>0){
			result.first();
			for(int i=0;i<RowNumber;i++){
			    current_tsn [i] = result.getString(1);
			    unit_name1 [i] = result.getString(2);
			    unit_name2 [i] = result.getString(3);
			    unit_name3 [i] = result.getString(4);
			    unit_name4 [i] = result.getString(5);
			    dateCreated [i]= result.getString(11);
			    scientificName [i] = result.getString(6);
			    taxonAuthorId [i] = result.getString(7);
			    parent_tsn [i] = result.getString(8);
			    rank_name [i] = result.getString(9);
			    userId [i] = result.getString(10);
			    annotation [i] = result.getString(12);
			    usage [i] = result.getString(13);
			    unaceptReason [i] = result.getString(14);
			    result.next();
			}
		   
			DateFormat df = new SimpleDateFormat("yyyy-MM-dd");
			for(int i=0;i<RowNumber;i++){

			    Date d1=df.parse(dateCreated[i]);
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
			    if(dateCheck==1){
				if(unit_name1[i].compareTo("null")!=0){
				    label = new Label(0,row+1,unit_name1[i]);
				    sheet2.addCell(label);
				}
				if(unit_name2[i].compareTo("null")!=0){
				    label = new Label(1,row+1,unit_name2[i]);
				    sheet2.addCell(label);
				}
				if(unit_name3[i].compareTo("null")!=0){
				    label = new Label(2,row+1,unit_name3[i]);
				    sheet2.addCell(label);
				}
				if(unit_name4[i].compareTo("null")!=0){
				    label = new Label(3,row+1,unit_name4[i]);
				    sheet2.addCell(label);
				}
				label = new Label(6,row+1,rank_name[i]);
				sheet2.addCell(label);
				label = new Label(5,row+1,usage[i]);
				sheet2.addCell(label);
				label = new Label(7,row+1,func.GetParent(parent_tsn[i]));
				sheet2.addCell(label);
				
				label = new Label(10,row+1,func.GetSubmitter(userId[i]));
				sheet2.addCell(label);
				
				if(taxonAuthorId[i]!="0"){
				    label = new Label(4,row+1,func.GetAuthor(taxonAuthorId[i]));
				    sheet2.addCell(label);
				}
				ParseAnnotation(annotation[i]);
				row=row+1;
			    }
			    else
				continue;
			}
		    }
		    if(HasChildren(children[j])==true)
			FindChildInformation(children[j],arguments,func);
		    continue;
	    }
	}catch(Exception e){
	    e.printStackTrace();
	    System.exit(1);
	}
    }//end of FindChildInformation

    //public method to parse annotation comments
    public static void ParseAnnotation(String annotation){
	
	String inputStr = "";
	String patternStr = "";
	String [] fields=null;
	WritableSheet sheet2 = copy.getSheet(1);
	Label label=null;
	try{
	    inputStr = annotation;
	    patternStr = "<br/>";
	    fields = inputStr.split(patternStr,-1);
	    String comment="";
	    for(int j=0;j<fields.length;j++){
		if(fields[j].compareTo("")!=0){
		    String whole = fields[j].substring(3,fields[j].length());
		    //System.out.println("whole string is " + whole);
		    String first = whole.substring(0,whole.indexOf("</b>")).trim();
		    String second = whole.substring(whole.indexOf("</b>")+4,whole.length()).trim();
		    //System.out.println("first is " + first + " second is " + second);
		    
		    if(first.compareTo("Name should be:")==0){
			label = new Label(9,row+1,second);
			sheet2.addCell(label);
			if(kingdom.compareTo("1")==0 || kingdom.compareTo("2")==0 || kingdom.compareTo("5")==0)
			    label = new Label(5,row+1,"invalid");
			else
			    label = new Label(5,row+1,"not accepted");
		    }
		    if(first.compareTo("Taxon Author should be:")==0){
			label = new Label(4,row+1,second);
			sheet2.addCell(label);
		    }
		    if(first.compareTo("Should be made synonym of:")==0){
			if(kingdom.compareTo("1")==0 || kingdom.compareTo("2")==0 || kingdom.compareTo("5")==0)
			    label = new Label(5,row+1,"invalid");
			else
			    label = new Label(5,row+1,"not accepted");
			sheet2.addCell(label);
			label = new Label(9,row+1,second);
			sheet2.addCell(label);
			label = new Label(8,row+1,"synonym");
                        sheet2.addCell(label);
		    }
		    if(first.compareTo("Should be removed from synonymy!Reasons:")==0){
			if(kingdom.compareTo("1")==0 || kingdom.compareTo("2")==0 || kingdom.compareTo("5")==0)
			    label = new Label(5,row+1,"valid");
			else
			    label = new Label(5,row+1,"accepted");
			comment = second;
		    }
		    if(first.compareTo("Should be child of:")==0){
			if(kingdom.compareTo("1")==0 || kingdom.compareTo("2")==0 || kingdom.compareTo("5")==0)
			    label = new Label(5,row+1,"invalid");
			else
			    label = new Label(5,row+1,"not accepted");
			sheet2.addCell(label);
			String tsn = second.substring(1,second.indexOf("]")).trim();
			String name = second.substring(second.indexOf("-")+1,second.length()).trim();
			name = name + " " + func.GetAuthorFromTSN(tsn);
			label = new Label(7,row+1,name);
			sheet2.addCell(label);
		    }
		    if(first.compareTo("Unaccepted reasons are:")==0){
			label = new Label(8,row+1,second);
			sheet2.addCell(label);
		    }
		    if(first.compareTo("Additional comments:")==0){
			comment = comment + second ;
		    }
		    if(first.compareTo("Reference should be:")==0){
			String referenceId = second.substring(1,second.indexOf("]"));
			WritePublication(referenceId);
		    }
		    
		    label = new Label(11,row+1,comment);
		    sheet2.addCell(label);
		}
		continue;
	    }
	}catch(WriteException we){
	    we.printStackTrace();
	    System.exit(1);
	}
    }//end of ParseAnnotation
    
    //public method to extract and write the Publication information
    public static void WritePublication(String publicationId){
	
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
		label = new Label(20,row+1,result_query.getString(9));
		sheet2.addCell(label);
		label = new Label(21,row+1,result_query.getString(16));
		sheet2.addCell(label);
		label = new Label(22,row+1,result_query.getString(17));
		sheet2.addCell(label);
		label = new Label(19,row+1,result_query.getString(18));
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
    
}//end of class SingleNameAnnotations
