/**
 * File: formMethods Replaces general.js
 */

// Load for no conflict with existing js libraries
// jQuery.noConflict();

$(document).ready(function(){
	jQuery.validator.addMethod("visible", function(value, element) {
		return $(element).is(":hidden") || !this.optional(element);
	}, "This is required when visible");
	jQuery.validator.addMethod("checkRequired", function(value, element) {
		return checkTypeRequire(element) || !this.optional(element);
	}, "This is required");
	$('form').keypress(function(e) {
		if (e.which == 13) {
	        var $targ = $(e.target);
	        if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
	            var focusNext = false;
	            $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
	                if (this === e.target) {
	                    focusNext = true;
	                }
	                else if (focusNext){
	                    $(this).focus();
	                    return false;
	                }
	            });
	            return false;
	        }
	    }
	});
	
	// Clear form function
	$.fn.clearForm = function() {
		return this.each(function() {
			var type = this.type, tag = this.tagName.toLowerCase();
			if (tag == 'form')
				return $(':input',this).clearForm();
			if (type == 'text' || type == 'password' || tag == 'textarea')
				this.value = '';
			else if (type == 'checkbox' || type == 'radio')
				this.checked = false;
			else if (tag == 'select')
				this.selectedIndex = -1;
		});
	};
	$('#clearFrm').click(function(){
		$('form').clearForm();
	});
	
	// Login Form
	$('#frmLogin').validate({
		rules: {
			username: "required",
			password: "required"
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	// Edit user form
	$('#frmEditUser').validate( {	
		rules: {
			pin: {minlength:6, maxlength:12},
			confirm_pin: {equalTo: "#pin"},
			last_name: "required",
			first_name: "required",
			email: {required: true, email: true},
			affiliation: "required"
		},
		messages: {
			pin: { minlength:"Password must have at least 6 characters",
					maxlength:"Password must have no more than 12 characters" },
			passwordagain: "Your passwords are different"
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	// Add user form
	$('#frmAddUser').validate( {	
		rules: {
			pin: { required: true, minlength:6, maxlength:12},
			confirm_pin: {equalTo: "#pin"},
			last_name: "required",
			first_name: "required",
			email: {required: true, email: true},
			affiliation: "required",
			uin: { remote: "/ajax/checkFormValues.php?action=check_uin" },
			country: "required"
		},
		messages: {
			pin: { required: "Password is required", 
						min:"Password must have at least 6 characters",
						max:"Password must have no more than 12 characters"},
			confirm_pin: "Your passwords are different",
			uin: { remote: jQuery.format("User name already exists") }
		},
		onkeyup: false,
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	// Add user form
	$('#frmNewUser').validate( {	
		rules: {
			pin: { required: true, minlength:6, maxlength:12},
			confirm_pin: {equalTo: "#pin"},
			last_name: "required",
			first_name: "required",
			email: {required: true, email: true},
			affiliation: "required",
			uin: { remote: "/ajax/checkFormValues.php?action=check_uin" },
			userresume: "required",
			country: "required",
			spamcode: { required: true, remote: "/ajax/checkFormValues.php?action=check_spam&spamid="+$('#spamid').val() }
		},
		messages: {
			pin: { required: "Password is required", 
						min:"Password must have at least 6 characters",
						max:"Password must have no more than 12 characters"},
			confirm_pin: "Your passwords are different",
			uin: { remote: jQuery.format("User name already exists") },
			spamcode: { remote: jQuery.format("Spam verification incorrect") }
		},
		onkeyup: false,
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	// Add user form
	$('#frmReviewer').validate( {
		errorLabelContainer: $("div.frmError"),
		rules: {
			pin: { required: true, minlength:6, maxlength:12},
			pin_re: {equalTo: "#pin"},
			uin: { remote: "/ajax/checkFormValues.php?action=check_uin" }
		},
		messages: {
			pin: { required: "Password is required", 
						min:"Password must have at least 6 characters",
						max:"Password must have no more than 12 characters"},
			pin_re: "Your passwords are different",
			uin: { remote: jQuery.format("User name already exists") }
		},
		onkeyup: false,
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	// Reset user password form
	$('#frmResetPasword').validate( {	
		rules: {
			email: {required: true, email: true}
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	// Annotation form
	$('#frmAnnotate').validate( {	
		rules: {
			title: "required",
			comment: "required",
			sourceOfId: "required",
			resourcesused: "required",
			Taxon: { required: function(element) { 
				var val = $("#typeDetAnnotation").val();
				return ($('#ddiv').is(':visible') && (val == 'agree' || val == 'disagree' || val == 'agreewq'));
			}},
			Determination: { required: function(element) { 
				var val = $("#typeDetAnnotation").val();
				return ($('#ddiv').is(':visible') && val == 'newdet');
			}},
		    sourceOfId: {required: "div#parent:visible"},
		    resourcesused: {required: "div#parent:visible"}
		},
		messages: {
			title: "Title is required",
			comment: "Comment is required",
			sourceOfId: "Source of identification required",
			Taxon: "Please select Taxon",
			Determination: "Please select taxon name",
			sourceOfId: "Source of id required",
			resourcesused: "Resources used in determination is required"
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	// Form changes for Annotation
	if ($('#frmAnnotate').length > 0) {
		if ($('#annotationType').val() == 'Determination') $('#ddiv').show();
		$('#annotationType').change(function(){
			var val = $(this).val();
			if (val == "TaxonName") location.href = $('#taxon_name').val();
			if (val == 'Determination') {
				$('#ddiv').show();
			} else {
				$('#ddiv').hide();
				$('#title').val('');
			}
			(val == "XML") ? $('#XML').show() : $('#XML').hide();
		});
		$('#typeDetAnnotation').change(function(){
			var val = $(this).val();
            (val == "agreewq" || val == "newdet") ? $('#prepost').show() : $('#prepost').hide();
            if (val == 'agree' || val == 'disagree' || val == 'agreewq') {
            	$('#determinationTD').hide();
            	$('#Determination').val('');
            	$('#tsnId').val('0');
            } else {
            	$('#determinationTD').show();
            }
            if (val == 'newdet') {
            	$('input[name=Taxon]:radio').attr("checked", false);
            }
            	
		});
	}
	
	/* User search form */
	$('#frmUserSearch').validate({
		rules: { term: "required" },
		messages: { term: "Required" },
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	/* News add form */
	$('#frmNews').validate({
		rules: { title: "required", body: "required" },
		messages: { title: "Title Required", body: "News script required" },
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	/* Group member form */
	if ($('#frmMemberGroup').length > 0) {
		$.validator.addMethod("checkRole", function(value, element) { 
			var cnt = 0;
			var bool;
			$('.duplicate').each(function(){
				cnt += $(this).val() == 'coordinator' ? 1 : 0;
			});
			var bool = cnt > 1 ? false : true;
			return this.optional(element) || bool;
			}, "Only one coordinator can be selected");
		
		$.validator.addClassRules("duplicate", { checkRole: true });
		$('#frmMemberGroup').validate({ errorLabelContainer: $("div.frmError") });
	}
	
	/* Add new group */
	$('#grpName').focus(function(){ $('#grpName').val(''); });
	$('#frmAddGroup').validate({
		onkeyup: false,
	    errorPlacement: function(error, element) {
	       error.appendTo($("#grpName"));
	    },
	    showErrors: function(errorMap, errorList){
	    	if (errorList.legnth > 0) {
	    		$("#grpName").val(errorList[0].message);
	    	}
	    	this.defaultShowErrors();
	    },
		rules: { 
			groupname: {
				required: true,
				remote: "/ajax/checkFormValues.php?action=check_group&id="+$('#id').val()
			}
		},
		messages: {
			groupname: { required: "Name required", remote: jQuery.format("Group name exits") }
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	/* Common forms validator */ 
	var validator = $('.frmValidate').validate({
		rules: {
			/* Specimen */
			BasisOfRecord: "required",
			TypeStatus: "required",
			IndividualCount: { digits: true },
			DateDetermined: { dateISO: true },
			DateCollected: { dateISO: true },
			earliestDateCollected: { dateISO: true },
			latestDateCollected: { dateISO: true },
			LocalityId: { digits: true, remote: "/ajax/checkFormValues.php?action=check_locality" },
			
			/* Shared Specimen, View and Group */
			tsnId: { required: true, digits: true, remote: "/ajax/checkFormValues.php?action=check_tsn" },
			
			/* Group */
			groupname: {
				required: true,
				remote: "/ajax/checkFormValues.php?action=check_group&id="+$('#id').val()
			},
				
			/* Taxon */
			sname: { required: true },
			rank_id: { required: true },
			reference_id: { remote: "/ajax/checkFormValues.php?action=check_reference" },
			parent_tsn: { required: true, digits: true, remote: "/ajax/checkFormValues.php?action=check_tsn" },
			'vernacularNameAdd[]': {
				required: {
					depends: function(element) {
						return $("input[name=languageAdd\\[\\]]").val() != '';
				    }
				}
			},
			'languageAdd[]': {
				required: { 
					depends: function(element) {
						return $("input[name=vernacularNameAdd\\[\\]]").val() != '';
			    	}
				}
			},
			'vernacularName[]': {
				required: {
					depends: function(element) {
						return $("input[name=languageAdd\\[\\]]").val() != '';
				    }
				}
			},
			'language[]': {
				required: { 
					depends: function(element) {
						return $("input[name=vernacularNameAdd\\[\\]]").val() != '';
			    	}
				}
			},

			
			/* View */
			StandardImage: { number: true, remote: "/ajax/checkFormValues.php?action=check_image" },
			
			/* Image */
			ImageFile: { required: function(element) { return $("#imageThumb").length == 0; } },
			Magnification: { number: true },
			
			/* Locality */
			Latitude: { number: true, min: 0, max: 90 },
			Longitude: { number: true, min: 0, max: 180 },
			CoordinatePrecision: { number: true },
			CoordinatePrecision: { number: true },
			MinimumElevation: { number: true },
			MaximumElevation: { number: true },
			MinimumDepth: { number: true },
			MaximumDepth: { number: true },
			
			/* Publication */
			publicationtitle: { checkRequired: true },
			title: { checkRequired: true },
			author: { checkRequired: true },
			year: { required: true, digits: true, minlength: 4, maxlength: 4 },
			day: { digits: true, min: 1, max: 31 },
			pages: { checkRequired: true },
			chapter: { checkRequired: true },
			volume: { checkRequired: true },
			editor: { checkRequired: true },
			school: { checkRequired: true },
			publisher: { checkRequired: true },
			address: { checkRequired: true },
			
			/* Shared */
			DateToPublish: { dateISO: true },
			'extLinkTypeId[]': { visible: true, checkRequired: true },
			'extLinkTypeIdAdd[]': { visible: true, checkRequired: true },
			'linkLabel[]': { visible: true },
			'linkLabelAdd[]': { visible: true },
			'linkUrlData[]': { visible: true, url: true },
			'linkUrlDataAdd[]': { visible: true, url: true },
			'refDescription[]': { visible: true },
			'refDescriptionAdd[]': { visible: true },
			'refExternalId[]': { visible: true, remote: "/ajax/checkFormValues.php?action=check_exist_ref&refLinkId="+$("input[name=reflinkId\\[\\]]").val() },
			'refExternalIdAdd[]': { visible: true, remote: "/ajax/checkFormValues.php?action=check_unique_ref" }
		},
		messages: {
			/* Specimen */
			BasisOfRecord: "Basis of Record required.",
			TypeStatus: "Type Status required",
			DateDetermined: { dateISO: "Enter date in YYYY-MM-DD format" },
			DateCollected: { dateISO: "Enter date in YYYY-MM-DD format" },
			earliestDateCollected: { dateISO: "Enter date in YYYY-MM-DD format" },
			latestDateCollected: { dateISO: "Enter date in YYYY-MM-DD format" },
			LocalityId: { remote: jQuery.format("Locality does not exist") },
			
			/* Shared Specimen, View and Group */
			tsnId: { required: "Taxon required", remote: jQuery.format("Taxon Id does not exist") },
			
			/* Group */
			groupname: { required: "Name required", remote: jQuery.format("Name already exists") },
				
			/* Taxon */
			sname: { required: "Taxon name required" },
			rank_id: { required: "Rank identification required" },
			reference_id: { remote: jQuery.format("Reference Id does not exist") },
			parent_tsn: { required: "Taxon required", remote: jQuery.format("Taxon Id does not exist") },
			'vernacularNameAdd[]': { required: "Name required" },
			'languageAdd[]': { required: "Language required" },
			'vernacularName[]': { required: "Name required" },
			'language[]': { required: "Language required" },
			
			/* View */
			StandardImage: { number: "Please enter decimals only", remote: jQuery.format("Image Id does not exist") },
			
			/* Image */
			ImageFile: "Image required",
			SpecimenId: { required: "Specimen required", remote: jQuery.format("Specimen Id does not exist") },
			ViewId: { required: "View Id required", remote: jQuery.format("View Id does not exist") },
			Magnification: { number: "Please enter decimals only" },
			
			/* Locality */
			Latitude: {number: "Please enter decimals only", min: "Minimum number is 0.0000", max: "Maximum number is 90.000" },
			Longitude: {number: "Please enter decimals only", min: "Minimum number is 0.0000", max: "Maximum number is 180.000" },
			CoordinatePrecision: { number: "Please enter decimals only" },
			MinimumElevation: { number: "Please enter decimals only" },
			MaximumElevation: { number: "Please enter decimals only" },
			MinimumDepth: { number: "Please enter decimals only" },
			MaximumDepth: { number: "Please enter decimals only" },
			
			/* Publication */
			publicationtitle: { checkRequired: "Publication title required" },
			title: { checkRequired: "Article title required" },
			author: "Author required",
			year: { checkRequired: "Year is required", digits: "Enter year in YYYY format", minlength: "Enter year in YYYY format", maxlength: "Enter year in YYYY format" },
			day: { digits: "Numbers only allowed", min: "Minimum day allowed is 1", max: "Maximum day allowed is 31" },
			pages: { checkRequired: "Pages is required" },
			chapter: { checkRequired: "Chapter is required" },
			volume: { checkRequired: "Volume is required" },
			editor: { checkRequired: "Editor is required" },
			school: { checkRequired: "School is required" },
			publisher: { checkRequired: "Publisher is required" },
			address: { checkRequired: "Publisher address is required" },
			
			/* Shared */
			DateToPublish: { dateISO: "Enter date in YYYY-MM-DD format" },
			'extLinkTypeId[]': { visible: "Link type required", checkRequired: "Link type required" },
			'extLinkTypeIdAdd[]': { visible: "Link type required", checkRequired: "Link type required" },
			'linkLabel[]': { visible: "Label required" },
			'linkLabelAdd[]': { visible: "Label required" },
			'linkUrlData[]': { url: "Please enter valid URL (http://)", visible: "Url required" },
			'linkUrlDataAdd[]': { url: "Please enter valid URL (http://)", visible: "Url required" },
			'refDescription[]': "Description required",
			'refDescriptionAdd[]': "Description required",
			'refExternalId[]': { visible: "Unique reference required", remote: jQuery.format("Reference already exists") },
			'refExternalIdAdd[]': { visible: "Unique reference required", remote: jQuery.format("Reference already exists") }
		},
		submitHandler: function(form) {
			// If publication form, clear all non-visible fields of data
			if (form.id == 'addPublication' || form.id == 'editPublication') {
			    $('tr:hidden', '#mytable').each(function(){
			    	var input = $(this).find(':input');
			    	input.val('');
				});
			    if ($('#extlinks').is(":hidden")) {
			    	$('tr', '#extlinks').each(function(){
				    	var input = $(this).find(':input');
				    	input.val('');
					});
			    }
			    if ($('#extrefs').is(":hidden")) {
			    	$('tr', '#extrefs').each(function(){
				    	var input = $(this).find(':input');
				    	input.val('');
					});
			    }
			}
			var count = $('#count');
			if (count.length == 0 || count.val() == 0 ){
				form.submit();
			}
			else{ // This is an edit that affects other related objects.
				window.frm = form;
				var objId = $('#objId').val();
				var objType = $('#objType').val();
				var objRelated = $('#objRelated').val();
				var objAction = $('#objAction').val();
				confirmChange(objId, objType, objRelated, objAction, count.val());
			}
		}
	});
	// Enable autocomplete
	if ($('.autocomplete').length > 0) {
		if ($('.vernacular').length > 0) {
			$('.vernacular').autocomplete({
				source: '/ajax/checkFormValues.php?action=vernacular',
				minLength: 2,
				delay: 100
			});
		}
		if ($('.taxonAuthor').length > 0) {
			$('.taxonAuthor').autocomplete({
				source: function(request, response){
					$.ajax({
						url: "/ajax/checkFormValues.php?action=taxon_author",
						dataType: "json",
						data: {
							term: request.term,
							kingdomid: $('#kingdomid').val()
						},
						success: function(data) {
							response($.map(data, function(item) {
								return item;
							}));
						}
					});
				},
				minLength: 4,
				delay: 100
			});
		}
		if ($('.country').length > 0) {
			$('.country').autocomplete({
				source: '/ajax/checkFormValues.php?action=country',
				minLength: 2,
				delay: 200
			});
		}
	}
	
	// Toggle tables
	$('.toggleTable').click(function(e){
		$(this).toggle().next('table').toggle();
		e.preventDefault();
	});
	
	// Remove rows for external links and refs
	$('.removeRow').click(function(e){
		var tbl = $(this).closest('table');
		var tblId = $(tbl).attr('id');
		var isEdit = $(tbl).attr('class') == 'edit' ? true : false; 
		var rowsExist = $(tbl).attr('title');
		var rowCount = $('#'+tblId+' tr').length;
		if(rowCount > rowsExist){
			$('tbody tr:last', tbl).remove();
		}
		else if(rowCount == rowsExist && !isEdit) {
			tbl.hide();
			tbl.prev('a.toggleTable').show();
			$('#'+tblId+' :input').each(function(){	$(this).val(''); });
		}
		e.preventDefault();
	});
	
	// Add row for external links or references
	$('.addRow').click(function(e){
		var tbl = $(this).closest('table');
		var tblId = $(tbl).attr('id');
		var newId = $('#'+tblId+' tr').length - 1;
		var newRow = $('#'+tblId+' tr:last').clone();
		$(':input', newRow).each(function(){
			var id = this.id.split('_');
			$(this).val('').attr('id', id[0]+'_'+newId);
		});
		$('#'+tblId+'> tbody:last').append(newRow);
		if ($('.findVernacular').length > 0) autoComplete();
		e.preventDefault();
	});
	
	// Add Publication - Publication Type select input
	$('#publicationtype').change(function(){
	    var type = $(this).val();
	    // Reset any existing errors
	    validator.resetForm();

	    // Reset rows to show all
	    $('tr', '#mytable').each(function(){
	    	var input = $(this).find(':input');
	    	// if (input.val() != type && input.attr('id') != 'Contributor')
			// input.val('');
	    	$(this).show();
		});
		
	    // Clear all asterisks
	    $('.asterisk').each(function(){
			$(this).html('');
		});
	   
	    $('#pt, #au, #ye').html('*').css('color', 'red');
	        
	    if (type != 'conference') {
	    	$('#organization').hide();
	    }
	    
	    if (type != 'thesis') {
	    	$('#school').hide();
	    }
	    else {
	        $('#sc').html('*');
	    }
	    
	    if (type != "journal_article" && type != "techreport") {
	        $('#number').hide();
	    }
	    
	    if (type != "incollection" && type != "inseries") {
	    	$('#series').hide();
	    }
	    
	    if (type != "book" && type != "inbook") {
	    	$('#chapter').hide();
	    }
	    
	    if (type == "inbook") {
	    	$('#ch, #ed').html('*').css('color', 'red');
	    }
	    
	    if (type != "journal_article" && type != "book" && type != "inseries") {
	    	$('#volume').hide();
	    }
	    
	    if (type == "unpublished") {
	    	$('#institution, #publisher, #address, #publishway, #month, #day, '+ 
	    	  '#issn, #isbn, #edition, #publicationtitle, #doi').hide();
	        $("#pt").html('');	        
	    }

	    if (type == "book" || type == "inbook" || type == "booklet" || type == "manual" || 
	    	type == "techreport" || type == "misc" || type == "thesis" || type == "web_publication") {
	        $('#at').html('');
	        $('#title').hide();
	    }
	    else if (type == "inseries") {
	        $('#at').html('');
	    }
	    else {
	    	$('#at').html('*').css('color', 'red');
	    }
	    
	    if (type == "book") {
	        $('#ch').html('');
	        $('#pu, #pa').html('*').css('color', 'red');
	    }
	    
	    if (type == "journal_article") {
	        $('#vo').html('*').css('color', 'red');
	    }
	    if (type == "journal_article" || type == "inbook") {
	    	$('#pg').html('*').css('color', 'red');
	    }
	    
	    if (type == "web_publication") {
	    	$('#extlinks').show();
	    }
	}).trigger('change');
});

// returning true means element not required
function checkTypeRequire(element) {
	// For values not required on other forms
	if ($('#frmPublication').length == 0) return true;
	
	var type = $("#publicationtype").val();
	switch(element.name){
		case 'publicationtitle':
			return type != 'unpublished' ? false : true;
		case 'title':
			var titleArray = ['conference', 'incollection', 'inproceedings', 'journal_article', 'proceedings', 'unpublished'];
			return jQuery.inArray(type, titleArray) > -1 ? false : true;
		case 'pages':
			var pageArray = ['journal_article', 'inbook'];
			return jQuery.inArray(type, pageArray) > -1 ? false : true;
		case 'chapter':
			return type == 'inbook' ? false : true;
		case 'volume':
			return type == 'journal_article' ? false : true;
		case 'editor':
			return type == 'inbook' ? false : true;
		case 'publisher':
		case 'address':
			return type == 'book' ? false : true;
		case 'school':
			return type == 'thesis' ? false : true;
		case 'extLinkTypeId[]':
		case 'extLinkTypeIdAdd[]':
			if (type == 'web_publication') {
				$('#extlinks').show();
				return false;
			}
			return true;
		case 'author':
			return false;
	}
	return true;
}

function pop(child, url) {
	if (child == 'Sex' || child == 'Form' || child == 'Stage') {
		child = window.open(url, child, 'directories=0,dependent=1,menubar=0,resizable=1,top=20,left=20,width=500,height=260,scrollbars=1, hotkeys =1', 'popup');
	}
	else if (child == 'TSN' || child == 'Locality' || child == 'Specimen' || child == 'View' || child == 'Image') {
        child = window.open(url, child, 'directories=0,dependent=1,menubar=0,resizable=yes,top=20,left=20,width=950, height=800,scrollbars=yes, hotkeys =1', 'popup');
	}
	else {
		child = window.open(url, child, "directories=0,dependent=1,menubar=0,resizable=1,top=20,left=20,width=550,height=250,scrollbars=1, hotkeys =1", 'popup');
	}
	if (window.focus) {
		child.focus();
	}
}

// TODO This function is being duplicated
function updateTSN(value, value2) {
	if ($('#frmEditTaxon').length > 0 || $('#frmAddTaxon').length > 0) {
		update("ParentTSN", value, value2);
	} else {
		update("TSN", value, value2);
	}
}

// TODO This function is being duplicated
function updatePublication(value, value2) {
	update("Publication", value, value2);
}

function update(child, value, name) {
	if (child == "TSN") {
		$('[name=tsnId]').val(value).focus();
		$('[name=Determination]').val(name);
	} else if (child == "ParentTSN") {
		$('[name=parent_tsn]').val(value).focus();
		$('[name=parentname]').val(name);
	} else if (child == "Location") {
		$('[name=LocalityId]').val(value).focus();
		$('[name=Locality]').val(name);
	} else if (child == "Sex") {
		$('[name=Sex]').append( $('<option></option>').val(name).html(value) ).val(name);
	} else if (child == "ImagingTechnique") {
		$('[name=ImagingTechnique]').append( $('<option></option>').val(name).html(value) ).val(name);
	} else if (child == "Preparation") {
		$('[name=ImagingPreparationTechnique]').append( $('<option></option>').val(name).html(value) ).val(name);
	} else if (child == "Part") {
		$('[name=SpecimenPart]').append( $('<option></option>').val(name).html(value) ).val(name);
	} else if (child == "Form") {
		$('[name=Form]').append( $('<option></option>').val(name).html(value) ).val(name);
	} else if (child == "Stage") {
		$('[name=DevelopmentalStage]').append( $('<option></option>').val(name).html(value) ).val(name);
	} else if (child == "Angle") {
		$('[name=ViewAngle]').append( $('<option></option>').val(name).html(value) ).val(name);
	} else if (child == "Image") {
		$('[name=StandardImage]').val(value);
	} else if (child == "Specimen") {
		$('[name=SpecimenId]').val(value).focus();
		$('[name=Specimen]').val(name);
	} else if (child == "View") {
		$('[name=ViewId]').val(value).focus();
		$('[name=View]').val(name);
	} else if (child == "Publication") {
		var part = name.split(";");
		// var author = part[0];
		// var year = part[1];
		// var author_year = author + "," + year;
		$('[name=reference_id]').val(value);
		$('[name=reference]').val(part[2]);
		// $('[name=author]').val(author_year);
	}
}

/*
 * Functions to confirm that changes are being made
 */

var winConfirm = null;

function confirmChange(objId, objType, objRelated, objAction, count){
    var windowWidth = 325;
    var windowHeight = 140;
    var locX = (screen.width - windowWidth) / 2;
    var locY = (screen.height - windowHeight) / 2;
    var windowFeatures = "width=" + windowWidth + ",height=" + windowHeight + ",screenX=" + locX + ",screenY=" + locY + 
    ",titlebar=no" + ",left=" + locX + ",top=" + locY + ",scrollbars = yes";
    
    var str = 'There are <span class="req">' + count + '</span> ' + objRelated + 's using this <br />' +
    objType + '.' + ' Do you want to ' + objAction + '?</b>';

    var buttonOpt = '<br /><br />' +
    '<a href="javascript: window.opener.confirmChoice(\''+objAction+'\', '+objId+');" class="button smallButton">' + '<div>Yes</div></a>&nbsp;' + 
    '<a href="javascript: this.close();" class="button smallButton">' + 
    '<div>Cancel</div></a>';
    
    if ((winConfirm != null) && !winConfirm.closed) {
        winConfirm.close();
    }

    winConfirm = window.open("", "winConfirm", windowFeatures);
    
    if (objAction == 'delete') {
        str = 'Do you want to continue?';
        if ($('#count').val() != 0) {
            str = 'There are <span class="req">' + count + '</span> ' + objRelated + 's using this ' +
            objType + '.' + 'Delete all ' + objRelated + 's first to <br /> delete this ' + objType + '</b>';
            
            buttonOpt = '<form name="buttonForm"><br />' +
            '<a href = "javascript: this.close();" class="button smallButton">' +
            '<div>Cancel</div></a>&nbsp;';
        }
    }
    
    var theHTML = '<html><head><title>Confirmation </title>' +
    '<link rel="stylesheet" title="Default" href="/style/morphbank2.css" type="text/css" media="screen"></link></head>' +
    '<body>' +
    '<div class="popContainer" style="width: 270px;">' +
    '<center><font="Verdana, Arial"><b>' +
    str +
    buttonOpt +
    '</font></center></div></body></html>';
    winConfirm.document.writeln(theHTML);
}

function confirmChoice(objAction, objId){
	winConfirm.close();
    if (objAction == 'change') {
        window.frm.submit();
    }
    else if (objAction == 'delete') {
        top.location = "../deleteObj.php?id=" + objId;
    }
}

function delbuttonclicked(delValue, ref) {
	top.location = '../deleteExtLink.php?extLink=' + delValue + '&ref=' + ref;
}

var delConfirm = null;
function confirmDelete(delValue, ref) {
        var deleteWidth = 300;
        var deleteHeight = 140;
        var X = ( screen.width - deleteWidth ) / 2;
        var Y = ( screen.height - deleteHeight ) / 2;
        var deleteFeatures = "width=" + deleteWidth
                                + ",height=" + deleteHeight
                                + ",screenX=" + X
                                + ",screenY=" + Y
                                + ",titlebar=no"
                                + ",left=" + X
                                + ",top=" + Y
                                + ",scrollbars = yes";

        if ( ( delConfirm != null ) && !delConfirm.closed ) {
                delConfirm.close();
        }

        delConfirm = window.open( "", "delConfirm", deleteFeatures );

        var delHTML = '<html><head><title>Confirmation </title>'
                      + '<link rel="stylesheet" title="Default" href="/style/morphbank2.css" type="text/css" media="screen"></link></head>'
                      + '<body><div class="popContainer" style="width: 250px;">'
                      + '<center><font="Verdana, Arial"><b>'
                      + 'Do you want to delete this entry?'
                      + '</b><form name="delForm"><br />'
                      + '<a href = "javascript: '
                      + 'opener.delbuttonclicked(' + delValue + ', \'' + ref + '\');'
                      + 'self.close();" class="button smallButton">'
                      + '<div>Yes</div></a>&nbsp;'
                      + '<a href = "javascript: '
                      + 'this.close();" class="button smallButton">'
                      + '<div>No</div></a>&nbsp;'
                      + '</form></font></center></div></body></html>';
// alert(delHTML);
        delConfirm.document.writeln( delHTML );
}
