<?php
// This script and data application were generated by AppGini 4.2 on 1/19/2009 at 1:01:05 PM
// Download AppGini for free from http://www.bigprof.com/appgini/download/

	include(dirname(__FILE__)."/defaultLang.php");
	include(dirname(__FILE__)."/language.php");
	include(dirname(__FILE__)."/lib.php");
	include(dirname(__FILE__)."/contacts_dml.php");

	// SQL query used in the filters page and the CSV file
	$filtersCSVQuery="select contacts.id as 'ID', contacts.contact_chamberid as 'Chamber #', contacts.contact_name as 'Business Name', contacts.contact_type as 'Business Type', contacts.contact_primary as 'Primary Contact', contacts.contact_secondary as 'Secondary Contact', contacts.contact_address as 'Address', contacts.contact_city as 'City', contacts.contact_state as 'State', contacts.contact_zip as 'Zip', contacts.contact_phone as 'Phone', contacts.contact_email as 'Contact email', contacts.contact_dns as 'DNS?', contacts.contact_lastyear as 'Donated Last Year?', contacts.contact_comments as 'Comments', rotarians16.rotarian_name as 'Rotarian' from contacts LEFT JOIN rotarians as rotarians16 ON contacts.rotarian_id=rotarians16.id ";
	// SQL query used in the table view
	$tableViewQuery="select contacts.id as 'ID', contacts.contact_chamberid as 'Chamber #', contacts.contact_name as 'Business Name', contacts.contact_type as 'Business Type', contacts.contact_primary as 'Primary Contact', contacts.contact_secondary as 'Secondary Contact', contacts.contact_address as 'Address', contacts.contact_city as 'City', contacts.contact_state as 'State', contacts.contact_zip as 'Zip', contacts.contact_phone as 'Phone', contacts.contact_email as 'Contact email', contacts.contact_dns as 'DNS?', contacts.contact_lastyear as 'Donated Last Year?', if(CHAR_LENGTH(contacts.contact_comments)>120, concat(left(contacts.contact_comments,120),' ...'), contacts.contact_comments) as 'Comments', concat(rotarians16.rotarian_name, '') as 'Rotarian' from contacts LEFT JOIN rotarians as rotarians16 ON contacts.rotarian_id=rotarians16.id ";

	// mm: can the current member access this page?
	$perm=getTablePermissions('contacts');
	if(!$perm[0]){
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">";
		echo "<div class=\"error\">".$Translation['tableAccessDenied']."</div>";
		exit;
	}

	$x = new DataList;
	$x->TableName = "contacts";
	$x->DataHeight = 150;
	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 0;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 1;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 0;
	$x->AllowCSV = 0;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 3;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "contacts_view.php";
	$x->RedirectAfterInsert = "contacts_view.php?SelectedID=#ID#";
	$x->TableTitle = "Contacts";
	$x->PrimaryKey = "contacts.id";
	$x->DefaultSortField = "3";
	$x->DefaultSortDirection = "asc";

	$x->ColWidth   = array(150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150);
	$x->ColCaption = array("ID", "Chamber #", "Business Name", "Business Type", "Primary Contact", "Address", "City", "Phone", "DNS?", "Donated Last Year?", "Comments", "Rotarian");
	$x->ColNumber  = array(1, 2, 3, 4, 5, 7, 8, 11, 13, 14, 15, 16);

	$x->Template = 'contacts_templateTV.html';
	$x->SelectedTemplate = 'contacts_templateTVS.html';
	$x->ShowTableHeader = 1;
	$x->ShowRecordSlots = 0;
	$x->HighlightColor = '#FFF0C2';
	if($HTTP_POST_VARS["Filter_x"] != ""  || $HTTP_POST_VARS['CSV_x'] != ""){
		// Query used in filters page and CSV output
		// mm: build the query based on current member's permissions
		if($perm[2]==1){ // view owner only
			$x->Query = $filtersCSVQuery.", membership_userrecords  where contacts.id=membership_userrecords.pkValue and membership_userrecords.tableName='contacts' and membership_userrecords.memberID='".getLoggedMemberID()."'";
		}elseif($perm[2]==2){ // view group only
			$x->Query = $filtersCSVQuery.", membership_userrecords  where contacts.id=membership_userrecords.pkValue and membership_userrecords.tableName='contacts' and membership_userrecords.groupID='".getLoggedGroupID()."'";
		}elseif($perm[2]==3){ // view all
			$x->Query = $filtersCSVQuery."";
		}elseif($perm[2]==0){ // view none
			$x->Query = "select 'Not enough permissions' from contacts";
		}
	}else{
		// Query used in table view
		// mm: build the query based on current member's permissions
		if($perm[2]==1){ // view owner only
			$x->Query = $tableViewQuery.", membership_userrecords  where contacts.id=membership_userrecords.pkValue and membership_userrecords.tableName='contacts' and membership_userrecords.memberID='".getLoggedMemberID()."'";
		}elseif($perm[2]==2){ // view group only
			$x->Query = $tableViewQuery.", membership_userrecords  where contacts.id=membership_userrecords.pkValue and membership_userrecords.tableName='contacts' and membership_userrecords.groupID='".getLoggedGroupID()."'";
		}elseif($perm[2]==3){ // view all
			$x->Query = $tableViewQuery."";
		}elseif($perm[2]==0){ // view none
			$x->Query = "select 'Not enough permissions' from contacts";
		}
	}

	// handle date sorting correctly
	// end of date sorting handler


	$x->Render();

	include(dirname(__FILE__)."/header.php");
	echo $x->HTML;
	include(dirname(__FILE__)."/footer.php");
?>