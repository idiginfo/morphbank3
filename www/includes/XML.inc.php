<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/
    
function GetXMLTable($XMLString)
  {   
     $CurrPTR = 0;
     $TableIndex = 0;
     $StringSize = strlen($XMLString);
     while ($CurrPTR < $StringSize )
      { 
      
//*********************************************************
// Look for the start of a tag...any tag                  *
//*********************************************************

         while(substr($XMLString,$CurrPTR,1) != '<' && $CurrPTR < $StringSize) 
                      $CurrPTR++;
         if($CurrPTR >= $StringSize) {  return;} 
//*********************************************************
//* Check the next character.                             *
//* If the Tag is the XML declaration, process            *
//*  instructions, or comment then discard the contents   *
//*  and look for the start of a new tag.                 *
//********************************************************
          $CurrPTR++;

        if(substr($XMLString,$CurrPTR,1) == '?' ||
            substr($XMLString,$CurrPTR,1) == '!'  )
              {   
                   while(substr($XMLString,$CurrPTR,1) != '>'   && $CurrPTR < $StringSize) 
		     {
                      $CurrPTR++; 
                      if($CurrPTR >= $StringSize) return; }
              }

//*********************************************************
//* Check to see if this is an end tag by looking at the  *
//*   first character past the '<' to see if it is a /.   *
//*   If so, then Pop the values off the Name, SpanSize,  *
//*   and values off the stack and place them in the      *
//*   and get rid of the end tag.                         *
//*********************************************************
          else if(substr($XMLString,$CurrPTR,1)=='/')
	    {  
                 while(substr($XMLString,$CurrPTR,1) != '>'   && $CurrPTR < $StringSize) 
                      $CurrPTR++; 
                 if($CurrPTR >=$StringSize) { return;}
                 
              }
//********************************************************
//*Else what we have is a beginning tag.  It can either   *
//*be the start of a tag that contains data or shows     *
//*a hierarchy.  At this point we only want to preserve  *
//*the name of the tag and store it in the stack.        *
//********************************************************

          else  
              {  
                 $TempCurrPTR = $CurrPTR; 
                 While(substr($XMLString,$TempCurrPTR,1) != ' ' &&
                       substr($XMLString,$TempCurrPTR,1) != '>'  && $TempCurrPTR < $StringSize)
                          $TempCurrPTR++;
                 $Length = $TempCurrPTR - $CurrPTR; 
                 $Name = substr($XMLString,$CurrPTR,$Length);
                 Echo '<TR><TD><B>'.$Name.'</B>';   
                 
 //********************************************************
//* Go to the end of the name tag by find the '>'         *
//*********************************************************
                  while(substr($XMLString,$CurrPTR,1) != '>'  && $CurrPTR < $StringSize) 
                      $CurrPTR++; 
                   if($CurrPTR >=$StringSize) { return;}
                  $CurrPTR++;

//*********************************************************
//*  Now we either want to find a value for the tag or    *
//*   realize that there is not a value and just another  *
//*   beginning tag.                                      *
//*  We advance the current point to the next place to    *
//*   Start the search.                                   *
//*  First, get rid of all white spaces.                  *
//*********************************************************
                 while((ctype_cntrl(substr($XMLString,$CurrPTR,1)) || 
                       ctype_space(substr($XMLString,$CurrPTR,1))  ||
                       substr($XMLString,$CurrPTR,1) == '-')  && $CurrPTR < $StringSize)
                          {
                           $CurrPTR++; }
                   $Length = 0;
                   $StartLoc = $CurrPTR; 
                   while(substr($XMLString,$CurrPTR,1) !='<'  && $CurrPTR < $StringSize)
                     {
                        $Length++;
                        $CurrPTR++;
                        if($CurrPTR >= $StringSize) return; 
                     }
                 
                  $Value = substr($XMLString,$StartLoc,$Length); 

                  if($Length==0)
                    ECHO '</TD></TR>';
                  else 
                    {if(IsURL($Value))
                         {
                          echo ':]<A HREF="'.$Value.'">'.$Value.'</A>]</td></tr>';
                         }
                      else
                          ECHO ':['. $Value.']</TD></TR>';   
                     }         
		  if($CurrPTR >= $StringSize) {  return;} 
              } 
	if($CurrPTR >= $StringSize) { return;} 
         }       
      }


function IsURL($Value)
{
     $File = @file($Value);
     if($File) return 1; 
      else return 0;
   } 



?>
