<script language = "JavaScript" type ="text/javascript">

	function removeRow(table){

		var tbl = document.getElementById(table);
		var lastRow = tbl.rows.length;
                var totalRows;
                if(table=='vernacular')
	           totalRows = document.forms[0].vernacularNum.value;
                else
                   totalRows = document.forms[0].extLinksNum.value;	

                if(totalRows > 1){
                	 if (lastRow > 3){
				tbl.deleteRow(lastRow - 1);
                                if(table=='vernacular')
				   document.forms[0].vernacularNum.value  =  totalRows-1 ;
                                else
                                   document.forms[0].extLinksNum.value  =  totalRows-1 ;	
			}else if (lastRow == 3){
                                if(table=='vernacular'){
				    document.getElementById('vernacular').style.display="inline";
				    document.forms[0].vernacularNum.value  =  1 ;
                                }
                                else{
                                    document.getElementById('extLinks').style.display="inline";
                                    document.forms[0].extLinksNum.value  =  1 ;
                                }	
			}
                }
	}

        function removeEditRow(table){

                var tbl = document.getElementById(table);
                var lastRow = tbl.rows.length;

                var vernacul = document.forms[0].vernacularNum.value;
                var vernacul_old = document.forms[0].vernacularNum_old.value;
                var exlink = document.forms[0].extLinksNum.value;
                var exlink_old = document.forms[0].extLinksNum_old.value;

                if(table=='vernacular'){
                    if(vernacul > vernacul_old){
                         if (lastRow > vernacul_old){
                                tbl.deleteRow(lastRow - 1);
                                document.forms[0].vernacularNum.value  =  vernacul-1 ;
                        }else if (lastRow == vernacul_old){
                                document.getElementById('vernacular').style.display="inline";
                                document.forms[0].vernacularNum.value  =  vernacul_old ;
                        }
                  }
               }
               else{
                         if (lastRow > 3){
                                tbl.deleteRow(lastRow - 1);
                                document.forms[0].extLinksNum.value  =  exlink-1 ;
                        }else if (lastRow == 3){
                                document.forms[0].extLinksNum.value  =  1;
                                document.forms[0].label0.value = "";
                                document.forms[0].url0.value = "";
                                document.forms[0].type0.value = '0';
                                document.forms[0].description0.value = "";
			}
               }	
        }


var language_selected;

function VernacularCheck(sel){


       var select_box = document.getElementById(sel);
//alert(select_box.value);
       if(select_box.value=="None of the above"){
          var box = document.createElement('input');
          box.setAttribute('type','text');
          box.setAttribute('id','lang_typed');
          box.setAttribute('name','lang_typed');
          box.setAttribute('size','15');
          box.setAttribute('maxlength','15');
          box.setAttribute('title','Enter the language of the vernacular');
          var row_sel = document.getElementById('sel_row');
          var td = document.createElement('td');
	  td.appendChild(box);
          row_sel.appendChild(td);
}
}

	
function addRow(table,selection){
		

	        var tbl = document.getElementById(table);
		var lastRow = tbl.rows.length;

		var types = new Array();
                var labels = new Array();
                var urls = new Array();
                var descriptions = new Array();
		var ids = new Array();
		var names = new Array();
                //var pairType = new Array();
                var pairType = selection.split(",");                


		if(table=='vernacular'){
                   //var pairLanguage = new Array();
		   //var pairLanguage = selection.split(",");


		   for(i =0; i < pairType.length; i++){
			
			//alert(pairType[i]);
			var language =  pairType[i];

			ids.push(language);	
			names.push(language);	

			//alert(ids + ' and ' + names);
		     }
                ids.push('Other');
                names.push('Other');

		// if there's no header row in the table, then iteration = lastRow + 1
		
		var iteration = lastRow + 1;
//alert("iteration is " + iteration);
		var row = tbl.insertRow(lastRow);

                //alert("start creating the cells in row " + row);
		
                var cellbox = row.insertCell(0);

                //alert("first cell created");

                var cellbox = row.insertCell(1);	
		var box = document.createElement('input');
                box.setAttribute ('type','text');
		box.setAttribute ('name','vernacular_name' + iteration);
		box.setAttribute ('id','vernacular_name' + iteration);
                box.setAttribute('size',"26");
                box.setAttribute('maxlength',"80");

		//alert(box.name);
                cellbox.appendChild(box);


                var cellbox = row.insertCell(2);
                var box = document.createElement('select');
                box.name =  'lang' + iteration;
                box.id =  'lang' + iteration;

                box.options[0] = new Option('-Select a language-', 0);
                for(i =0; i < (pairType.length + 1); i++){

                        box.options[i+1] = new Option(names[i], ids[i]);
                }
                
                language_selected = 'lang' + iteration;
		//alert(box.name);
                 
		cellbox.appendChild(box);
	 	
                var totalRows = tbl.rows.length;
		document.forms[0].vernacularNum.value  =  totalRows - 2 ;
//alert ("vernacular number " + document.forms[0].vernacularNum.value);		
            
	   }
           if (table=='extLinks'){

              for(i =0; i < pairType.length; i++){

		 //alert(pairType[i]);
                 var type =  pairType[i];

                 ids.push(type);
                 names.push(type);

             }
	      var iteration = lastRow + 1;
              var row = tbl.insertRow(lastRow);

              //alert("start creating the cells in row " + row);

              var cellbox = row.insertCell(0);

            
              var cellbox = row.insertCell(1);
              var box = document.createElement('select');
              box.name ='type' + iteration;
              box.id = 'type' + iteration;
              box.options[0] = new Option('-Select linkType-', 0);
              
              for(i =0; i < (pairType.length + 1); i++){

                  box.options[i+1] = new Option(names[i], ids[i]);
              }

                type_selected = 'type' + iteration;
            
                cellbox.appendChild(box);

                // lable input box
                var cellbox = row.insertCell(2);
                //cellbox.style.backgroundColor = "#F4F4F4";
                var box = document.createElement('input');
                box.setAttribute('type', 'text');
                box.setAttribute('name', 'label' + iteration);
                box.setAttribute('id', 'label' + iteration);
                box.setAttribute('size', '22');
                box.setAttribute('maxlength', '64');
            
                cellbox.appendChild(box);

                var cellbox = row.insertCell(3);
                var box = document.createElement('input');
                box.setAttribute('type', 'text');
                box.setAttribute('name', 'url' + iteration);
                box.setAttribute('id', 'url' + iteration);
                box.setAttribute('title', 'Please enter Absolute Url');
                box.setAttribute('size', '22');
                box.setAttribute('maxlength', '255');

                cellbox.appendChild(box);

                var cellbox = row.insertCell(4);
                var box = document.createElement('input');
                box.setAttribute('type', 'text');
                box.setAttribute('name', 'description' + iteration);
                box.setAttribute('id', 'description' + iteration);
                box.setAttribute('size', '22');
                box.setAttribute('maxlength', '255');

                cellbox.appendChild(box);

                var totalRows = tbl.rows.length;
                document.forms[0].extLinksNum.value  =  totalRows - 2 ;
                //alert(document.forms[0].extLinksNum.value);

               }	
	}


        //function checkLinks(table, field, fieldName){
        function checkLinks(table){

		totalLinks = document.forms[0].vernacularNum.value; 
                //if table  extLinks is displayed then check if url, label and type are empty
                if(document.getElementById(table).style.display=="inline"){
                        for(i = 1 ; i <= totalRows; i++){

                                var vernacular = document.getElementById('vernacular_name' + i);
                                var vernacularName = "vernacular" + i;
                                var language = document.getElementById('lang_sel' + i);
                                var languageSelected = "language" + i;

                                if (vernacular.value.length <= 0) {
                                        checklist += vernacularName + ' \t';
                                        flag = false;
                                }
                                if (language.value.length <= 0 || language.value=='0') {
                                        checklist += languageSelected;
                                        flag = false;
                                }

                                checklist += '\n';
                        }
                }

		return checklist;
	}
</script>
