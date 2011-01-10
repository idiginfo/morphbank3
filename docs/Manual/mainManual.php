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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

	
	include_once('head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>


	<div class="main">	<div class="mainGenericContainer" style="width: 820px;">
		<!--change the header below -->
		<h1>Sample Page Header</h1>
		<br /><br />
			<table class="manualContainer" cellspacing="0" width="100%">
			<tbody><tr>
			<td width="100%">
		
	<!-- enter -->
	<!-- text  -->
	<!-- below -->
		
		<!-- sample headers below -->

			<h1>sample h1 header</h1>
			<h2>sample h2 header</h2>
			<h3>sample h3 header</h3>

		<!-- sample text below -->			
			Morphbank is an open web repository of biological images documenting specimen-based research in comparative 
			anatomy, morphological phylogenetics, <a href="none.htm">taxonomy</a> and related fields focused on increasing 
			our knowledge about biodiversity. The project receives its main funding from the Biological Databases and 
			Informatics program of the National Science Foundation. Morphbank is developing cyber infrastructure to support 
			a wide array of biological disciplines such as taxonomy, systematics, evolutionary biology, plant science and 
			animal science.
			<br/>

			<br/>
			
		<!-- sample specialtext1 -->			
			<div class="specialtext1">
			Morphbank is an open web repository of biological images documenting specimen-based research in comparative 
			anatomy, morphological phylogenetics, taxonomy and related fields focused on increasing our knowledge about 
			biodiversity. 
			<br/>
			<br/>
			sample ul list:
			 <ul>
			 	<li>test</li>
			 	<li>test</li>

			 </ul>
			The project receives its main funding from the Biological Databases and Informatics program of 
			the National Science Foundation. Morphbank is <a href="hhh.htm">developing</a> cyber infrastructure to support a wide array of 
			biological disciplines such as taxonomy, systematics, evolutionary biology, plant science and animal science.
			</div>

		<!-- sample specialtext2 -->			
			<div class="specialtext2">
			Morphbank is an open web repository of biological images documenting specimen-based research in comparative 
			anatomy, morphological phylogenetics, <a href="hhh.htm">taxonomy and related</a> fields focused on increasing our knowledge about 
			biodiversity. 
			<br/>

			<br/>
			sample ul list:
			 <ul>
			 	<li>test</li>
			 	<li>test</li>
			 </ul>
			The project receives its main funding from the Biological Databases and Informatics program of 
			the National Science Foundation. Morphbank is developing cyber infrastructure to support a wide array of 
			biological disciplines such as taxonomy, systematics, evolutionary biology, plant science and animal science. 
			</div>

		<!-- sample specialtext3 -->
			<div class="specialtext3">
			Morphbank is an open web repository of biological images documenting specimen-based research in comparative 
			anatomy, morphological phylogenetics, taxonomy and related fields focused on increasing our knowledge about 
			biodiversity. The project receives its main funding from the Biological Databases and Informatics program of 
			the National Science Foundation. <a href="hhh.htm">Morphbank</a> is developing cyber infrastructure to support a wide array of 
			biological disciplines such as taxonomy, systematics, evolutionary biology, plant science and animal science. 
			</div>

		<!-- sample lists -->			
			sample ul list:
			
			 <ul>
			 	<li>test</li>

			 	<li>test</li>
			 </ul>
			 
			 sample ol list:
			
			 <ol>
			 	<li>test</li>
			 	<li>test</li>
			 </ol>
			 
	<!-- end -->
	<!-- text -->
	<!-- here -->
			

			</td>
			</tr>
			</tbody></table>
			</div>
		
<?php
    include( $includeDirectory.'manual_comments.php');
	?>

	
