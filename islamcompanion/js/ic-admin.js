var IC_Admin = {	
	/** The current form settings */
	current_settings: Array(),
	/** Indicates the progress of the data import */
	import_progress: 0,
	/** Used to indicate that the import completed successfully */
	data_import_completed: false,
	/** The current import action */
	import_action: "deleting custom posts",
	/** The current verse. The data import will start at this verse */
	next_verse: 0,
	/** The overlay object. this object is used to create the grayout affect before an ajax call */
	overlay                           : new Object(),
	/** The html id of the element where the grayout effect is created */
	overlay_div_id                    : "ic_settings_left",
	/** The number of verses to import in one try */		
	verse_import_count                : 0,
	/** The start time for the data import */
	start_time                        : 0,
	/** The total verse count */
	total_verse_count                 : 26485,
	/** The total number of verses imported */
	total_verses_imported             : 0,
	/**
	 * Used to show/hide the grayout overlay. 
	 * If action parameter is set to show, the overlay is displayed. otherwise the overlay is hidden	 
	 *
	 * @since 2.0.0
	*/ 
	ToggleOverlay: function(action)
	{
	    try {
			if(action=="show")
			    /** The overlay div is shown */
	        IC_Navigators.DisplayOverlay(true, "Admin");
			else 
			/** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Admin");
		}
		catch(err) {
		    alert(IC_L10n.data_fetch_alert);
		}		
	},
        /**
	 * Used to update the narrator dropdown
	 * It is called then the language dropdown is selected
	 * And when the settings page first loads
	 * 
	 * @since 2.0.0
         */
	UpdateNarratorDropdown: function()
	{
   	        /** The narrator dropdown is emptied */
		document.getElementById('ic_narrator').length = 0;
		/** The current value of the language dropdown is fetched */
		var ic_language                = document.getElementById("ic_language").value;
		/** The current value of the extra hidden field is fetched */
		var ic_extra                   = document.getElementById("ic_extra").value;
		/** The extra field is split on the '@' character */
		temp_arr                       = ic_extra.split("@");
		/** The first array element which is the meta data is base64 decoded and then json decoded */	  	
		var meta_data                  = JSON.parse(window.atob(temp_arr[0]));
		/** The second array element which is the narrator value is base64 decoded and then json decoded*/
		var ic_narrator                = JSON.parse(window.atob(temp_arr[1]));
 	        /** The narrator value stored in Wordpress */
		ic_narrator                    = ic_narrator['narrator'];
	        /** The index of the select box that should be selected */
	        selected_index                 = -1;
		for(var count=0; count<meta_data.languages_narrators.length; count++) {		  			
	            var temp_language      = meta_data.languages_narrators[count]['language'];
                    var temp_narrator      = meta_data.languages_narrators[count]['narrator'];				  			    var option             = document.createElement("option");
                    option.text            = temp_narrator;
                    option.value           = temp_narrator;
                    
		    if(ic_language==temp_language) {  					
                        if(ic_narrator == temp_narrator) selected_index = count;
                        document.getElementById("ic_narrator").add(option);
		    }
	        }
	        
	        if(selected_index > 0) document.getElementById("ic_narrator").value = ic_narrator;
	        else document.getElementById("ic_narrator").value = document.getElementById("ic_narrator").options[0].value;
	},
			
	/**
	 * Formats the admin page
	 *
	 * @since 2.0.0
	 */		 
	FormatAdminPage: function()
	{
	    document.getElementById("ic_division_number").parentNode.parentNode.classList.add("ic-hidden");
	    document.getElementById("ic_ayat").parentNode.parentNode.classList.add("ic-hidden");	
	    document.getElementById("ic_ruku").parentNode.parentNode.classList.add("ic-hidden");
	    document.getElementById("ic_sura").parentNode.parentNode.classList.add("ic-hidden");
	    document.getElementById("ic_hadith_book").parentNode.parentNode.classList.add("ic-hidden");
	    document.getElementById("ic_hadith_title").parentNode.parentNode.classList.add("ic-hidden");
	    document.getElementById("ic_hadith_source").parentNode.parentNode.classList.add("ic-hidden");
	    IC_Admin.UpdateNarratorDropdown();
	    document.getElementById("ic_form").classList.remove("ic-hidden");	
	},
	
	/**
	 * Toggles the data source option
	 *
	 * @param string force indicates if the import progress window should be displayed
	 *
	 * @since 2.0.0
	 */		 
	ToggleImportProgress: function(force)
	{
	    /** 	     
	     * If the data imported option is set to none
	     * Then the progress window is hidden
	     */
	    if(document.getElementById('ic_imported_data').value == "none") {
	    	document.getElementById('ic_import').classList.add("ic-hidden");
	    	document.getElementById('ic_import').classList.remove("button");
	    	document.getElementById('ic_import').classList.remove("button-primary");	    	
	    	document.getElementById('ic_settings_right').classList.add("ic-hidden");
	    }
	    /** Otherwise progress window is shown */
	    else {
	    	/** An alert message is shown asking the user to click on data import button */	    	
	    	document.getElementById('ic_import').classList.remove("ic-hidden");
	    	document.getElementById('ic_import').classList.add("button");
		document.getElementById('ic_import').classList.add("button-primary");
	    	document.getElementById('ic_settings_right').classList.remove("ic-hidden");
	    }
	},
	
	/** 
	 * Used to handle an error in the data import process
	 * 
	 * It shows an alert message and clears the import variables
	 * 
	 * @since   2.0.0
	 */
	HandleImportDataError: function() {
	    /** In case of error an alert message is shown */
	    alert(IC_L10n.data_import_error);		     
	    /** The overlay div is hidden */
	    IC_Admin.ToggleOverlay("hide");
	    /** The next verse and progress are set to 0 */
	    IC_Admin.next_verse            = 0;	 
	    IC_Admin.import_progress       = 0;
	    /** The import action is reset */
	    IC_Admin.import_action         = 'deleting custom posts';
	},
	
	/** 
	 * Used to update the import progress
	 * 
	 * It updates the import variables using the new progress value
	 * If the import is not completed, then it calls the import process again
	 * 
	 * @since   2.0.0
	 * @param array response the response from the server. it is an array with 2 keys:
	 * result => the result of the data import
	 * text => the details of the data import. it is an array with 3 keys:
	 * next_verse => the next verse at which to start the import
	 * progress => the import progress
	 * import_action => the import action
	 */
	UpdateImportProgress: function(response) {
	    try {
		/** If the import process is complete */
		if (response.data.progress >= 100) {
			IC_Admin.CompleteImportProgress();
			return;
		}
		/** The current unix timestamp */
        	var date                       = new Date();
                var current_time               = Math.floor(date.getTime()/1000);
                /** The time taken */
                var time_taken                 = (current_time - IC_Admin.start_time);
                /** If the time taken is 0 then time remaining is set to "Not Available" */
                if (time_taken > 0) {
                    /** The number of verses to be imported */
                    var verses_to_import           = (IC_Admin.total_verse_count - IC_Admin.next_verse);
                    /** The data import rate */
                    var import_rate                = Math.floor(IC_Admin.total_verses_imported/time_taken);
                    /** The time remaining */
                    var time_remaining             = Math.floor(verses_to_import / import_rate);
                    /** If the remaining time is greater than 1 hour */
                    if (time_remaining > 3600) {
                        time_remaining_hours       = Math.floor(time_remaining / 3600);
                        time_remaining_min         = Math.floor((time_remaining - (time_remaining_hours * 3600))/60);
                        time_remaining_sec         = time_remaining - ((time_remaining_hours * 3600) + (time_remaining_min * 60));
                        time_remaining             = (time_remaining_hours + " hrs, " + time_remaining_min + " min and " + time_remaining_sec) + " sec";
                    }
                    /** If the remaining time is greater than 1 minute but less than 1 hour */
                    else if (time_remaining > 60 && time_remaining < 3600) {
                        time_remaining_min         = Math.floor(time_remaining/60);
                        time_remaining_sec         = time_remaining - (time_remaining_min * 60);
                        time_remaining             = time_remaining_min + " min and " + time_remaining_sec + " sec";
                    }
                }
                else {
                    time_remaining             = "Not Available";
                }
		/** The import variables are set */
		IC_Admin.next_verse            = response.data.next_verse;
	        IC_Admin.total_verses_imported = (IC_Admin.total_verses_imported + IC_Admin.verse_import_count);
	        IC_Admin.import_progress       = response.data.progress;
		IC_Admin.import_action         = response.data.import_action;
		/** The progress bar is fetched */
		var progress_bar_element       = document.getElementById("ic-progress-bar");
		progress_bar_element.style.width = response.data.progress + '%';
                document.getElementById("ic-progress-bar-label").innerHTML      = response.data.progress * 1  + '%';
                document.getElementById("data_import_time_remaining").innerHTML = "<b>Time remaining: " + time_remaining + "</b>";
		/** The data is imported */
		IC_Admin.ImportData();
	    }
            catch(err) {
	        alert(IC_L10n.data_fetch_alert);
	    }	
	},
	
	/** 
	 * Used to update the import variables once the import process is complete
	 * 
	 * It resets the import variables
	 * It also shows an alert message to the user asking the user to save the settings
	 * 
	 * @since   2.0.0
	 */
	CompleteImportProgress: function() {	
		/** The import button is hidden */
		document.getElementById('ic_import').style.visibility = "hidden";
		/** The right section of the settings page is hidden */
	    document.getElementById('ic_settings_right').style.visibility = "hidden";
	    /** The overlay div is hidden */
	    IC_Admin.ToggleOverlay("hidden");
    	    /** If text of the button is set to "Delete Imported Data", then imported data is set to none */
	    if (document.getElementById('ic_import').value == "Delete Imported Data") {
	        /** The imported data is set to 'none' */
	        document.getElementById("ic_imported_data").value = "none";
	        /** The import status is updated */
	        document.getElementById("data_import_status").innerHTML = "Data was successfully removed";
	        /** The import complete message is shown */			
		alert(IC_L10n.data_removal_complete);
	    }
	    else {
	        /** The import status is updated */
	        document.getElementById("data_import_status").innerHTML = "Data Import completed successfully";
	        /** The import complete message is shown */			
		alert(IC_L10n.data_import_complete);
	    }
	    /** The import process completed successfully */
	    IC_Admin.data_import_completed  = true;
	},
	
	/**
	 * Used to saved the current form settings
	 * 
	 * It saves the original form values of the language and narrator fields	 
	 */
	SaveCurrentFormSettings: function()
	{
		/** The original language value is fetched */
		var language_value                            = document.getElementById("ic_language").value;
		/** The original narrator value is fetched */
		var narrator_value                            = document.getElementById("ic_narrator").value;
		/** The import data value is fetched */
		var imported_data                             = document.getElementById("ic_imported_data").value;
		
		/** The language value is saved to local variable */
		IC_Admin.current_settings['ic_language']      = language_value;
		
		/** The narrator value is saved to local variable */
		IC_Admin.current_settings['ic_narrator']      = narrator_value;
		/** The data source value is saved to local variable */
		IC_Admin.current_settings['ic_imported_data'] = imported_data;
	},
	
	/**
	 * Used to determine if the the form settings have changed
	 * 
	 * It is used to cancel the save changed button, if the user has not imported the data
	 * 
	 * @param string setting_id the html id of the setting that needs to be checked
	 * 
	 * @return boolean setting_changed used to indicate if the setting has changed
	 */
	IsFormSettingsChanged: function(setting_id)
	{
		/** The current setting value is fetched */
		var setting_value    = document.getElementById(setting_id).value;
		
		/** If the original setting value does not match the current setting value, then the function returns false */
		if (setting_value != IC_Admin.current_settings[setting_id])
		    setting_changed  = true;
		else 
		    setting_changed  = false;
		    
		return setting_changed;
	},
	
	/**
	 * Starts the data import
	 * 
	 * It makes ajax calls to the server for importing the data
	 * After each ajax call, it updates the progress bar
	 * 
	 * @since 2.0.0	 	
	 */		 
	ImportData: function()
	{
		/** The current value of the extra hidden field is fetched */
		var ic_extra                           = document.getElementById("ic_extra").value;
		/** The extra field is split on the '@' character */
		temp_arr                               = ic_extra.split("@");		
		/** The ajax nonce. it allows secure ajax calls to WordPress */	
		var ajax_nonce                         = temp_arr[2];
		/** The number of verses to import in one try */	
		var verse_import_count                 = temp_arr[3];
		/** The verse id at which to start the import */
		var next_verse                         = IC_Admin.next_verse;
		/** The current language */
		var language                           = document.getElementById("ic_language").value;
		/** The current narrator */
		var narrator                           = document.getElementById("ic_narrator").value;
		/** The data to import */
		var imported_data                      = document.getElementById("ic_imported_data").value;
		/** The current hadith language */
		var hadith_language                    = document.getElementById("ic_hadith_language").value;
		/** The parameters for the ajax call */		
		var parameters                         = {			
		        action: "dataimport",
			plugin_action: IC_Admin.import_action,
			imported_data: imported_data,
			security: ajax_nonce,
			next_verse: next_verse,
			language: language,
			narrator: narrator,
			hadith_language : hadith_language				
		};
		/** The number of verses to import in one try is set */
		IC_Admin.verse_import_count            = verse_import_count;
		/** The current action */
	        var current_action                     = IC_Admin.import_action.replace(/_/g," ");
	        /** If the current action is import_quranic_data */
	        if (IC_Admin.import_action == "importing quranic data" || IC_Admin.import_action == "importing hadith data")
	            /** The import status is updated */
	           document.getElementById("data_import_status").innerHTML = ("Current action: " + current_action + " (imported " + IC_Admin.next_verse + " verses)");
	        else
   	            /** The import status is updated */
	            document.getElementById("data_import_status").innerHTML = ("Current action: " + current_action);
	        
	         /** The overlay div is shown */
	         IC_Navigators.DisplayOverlay(true, "Hadith");
	         /** The ajax call is made */					
	         Utilities.MakeAjaxCall('/wp-admin/admin-ajax.php', "POST", parameters, IC_Admin.UpdateImportProgress, IC_Admin.HandleImportDataError);	     
	}
};
			 
	 window.addEventListener("load", function(){
	
	        if(document.getElementById("ic_narrator") !=undefined) {
		/** The current form settings are saved */
		IC_Admin.SaveCurrentFormSettings();
		
		if(document.getElementById("ic_narrator").innerHTML != undefined) {
		    IC_Admin.FormatAdminPage();
		}
		
		document.getElementById("ic_language").addEventListener("change", function(){
	     	    IC_Admin.UpdateNarratorDropdown();
	        });
		
		document.getElementById("ic_imported_data").addEventListener("change", function(){
	     	    IC_Admin.ToggleImportProgress();
	        });
	    	    
	        document.getElementById("ic_import").addEventListener("click", function(){
	            /** The current unix timestamp */
          	    var date         = new Date();
                    var current_time = Math.floor(date.getTime()/1000);
	            /** The progress window is shown */
	            IC_Admin.ToggleImportProgress("show");
	            /** The start time for the data import */
	            IC_Admin.start_time                    = current_time;
	    	    /** The overlay div is shown over the widget */
	            IC_Admin.ToggleOverlay("show");
	            /** If text of the button is set to "Delete Imported Data", then imported data is set to none */
		    if (document.getElementById("ic_import").value == "Delete Imported Data") {
		        document.getElementById("ic_imported_data").value = "none";
		    }
		    /** If the data to import is set to "hadith" */
		    else if (document.getElementById("ic_imported_data").value == "hadith") {
		        IC_Admin.import_action             = "importing hadith meta data";
		    }
		    /** If the data to import is set to "holy quran" */
		    else if (document.getElementById("ic_imported_data").value == "holy quran") {
		        IC_Admin.import_action             = "importing quranic author data";
		    }
		    /** If the data to import is set to "holy quran and hadith" */
		    else if (document.getElementById("ic_imported_data").value == "holy quran and hadith") {
		        IC_Admin.import_action             = "importing hadith meta data";
		    }
	            /** The data is imported */
	     	    IC_Admin.ImportData();
	        });
	        }
	});
