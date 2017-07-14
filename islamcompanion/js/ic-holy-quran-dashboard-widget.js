var IC_Holy_Quran_Dashboard_Widget    = {
        /** The overlay object. this object is used to create the grayout affect before an ajax call */
	overlay                           : new Object(),
	/** The html id of the element where the grayout effect is created */
	overlay_div_id                    : "ic-holy-quran-navigator-text",
	/** The	page number of the search results */
	page_number                       : "1",
	/** The search text */
	search_text                       : "",
	/** The search result id */
	search_result_id                  : "",	
	/** The current navigator mode */
	mode                              : "navigator",				
	/**
	 * Used to register the event handlers for the Holy Quran Dashboard Widget
	 * 
	 * @since 2.0.0 
	 */
	RegisterEventHandlers: function()
	{
	if (document.getElementById("ic-division-number") != undefined) {
		document.getElementById("ic-division-number").addEventListener("change", function(){
	     	IC_Holy_Quran_Dashboard_Widget.FetchVerseData("division_number_box");
	    });
        }
        
        if (document.getElementById("ic-sura") != undefined) {
		document.getElementById("ic-sura").addEventListener("change", function(){
	     	IC_Holy_Quran_Dashboard_Widget.FetchVerseData("sura_box");
	    });
	}
	
	if (document.getElementById("ic-ruku") != undefined) {
	    document.getElementById("ic-ruku").addEventListener("change", function(){
	     	IC_Holy_Quran_Dashboard_Widget.FetchVerseData("ruku_box");
	    });
	}
	
	if (document.getElementById("ic-holy-quran-next") != undefined) {
	    document.getElementById("ic-holy-quran-next").addEventListener("click", function(){
	        /** If the current navigator mode is 'search' */
	        if (IC_Holy_Quran_Dashboard_Widget.mode == 'navigator')
	     	    IC_Holy_Quran_Dashboard_Widget.FetchVerseData("next");
	        /** If the current navigator mode is 'search' */
	        else if (IC_Holy_Quran_Dashboard_Widget.mode == 'search') {
	            var page_number = IC_Holy_Quran_Dashboard_Widget.page_number;
	            /** If the current page number less than the number of items in the page number dropdown */
	            if (page_number < document.getElementById('ic-holy-quran-search-pages').length)
	                page_number++;
	     	    IC_Holy_Quran_Dashboard_Widget.SearchVerseData(page_number);
	     	}  
	    });
	}
	
	if (document.getElementById("ic-holy-quran-prev") != undefined) {
	    document.getElementById("ic-holy-quran-prev").addEventListener("click", function(){
	     	/** If the current navigator mode is 'search' */
	        if (IC_Holy_Quran_Dashboard_Widget.mode == 'navigator')
	     	    IC_Holy_Quran_Dashboard_Widget.FetchVerseData("previous");
	        /** If the current navigator mode is 'search' */
	        else if (IC_Holy_Quran_Dashboard_Widget.mode == 'search') {
	            var page_number = IC_Holy_Quran_Dashboard_Widget.page_number;
	            /** If the current page number more than 1 */
	            if (page_number > 1)
	                page_number--;
	     	    IC_Holy_Quran_Dashboard_Widget.SearchVerseData(page_number);
	     	}  
	    });
	}
	
	if (document.getElementById("ic-holy-quran-searchbox") != undefined) {
    	    document.getElementById("ic-holy-quran-searchbox").addEventListener("keydown", function(){
	     	/** If the event key was pressed */
	     	if (event.keyCode == 13) {
	     	    IC_Holy_Quran_Dashboard_Widget.SearchVerseData("1");
	     	}
	    });
	}
	    var word_list = document.getElementsByClassName("ic-holy-quran-widget-word");
	    
	    for (count = 0; count < word_list.length; count++) {
		    document.getElementsByClassName("ic-holy-quran-widget-word")[count].addEventListener("mouseenter", function(){
			/** The word id */
			var word_id = this.id;
			/** The word is highlighted */
		     	IC_Navigators.HighlightWord(word_id);
		    });
		    
	    	    document.getElementsByClassName("ic-holy-quran-widget-word")[count].addEventListener("click", function(){
	 	        /** The id of the html element where the mouse is currently positioned */
			var word_id = IC_Navigators.current_word_id
			/** The dictionary url */
			var dictionary_url = "";
			/** If the word id starts with 'holy-quran-translation-' */
			if (word_id.indexOf("holy-quran-translation-") == 0) {
			    dictionary_url = document.getElementById("ic-translated-dictionary-url").value;
			}
			/** If the word id starts with 'holy-quran-arabic-' */
			else if (word_id.indexOf("holy-quran-arabic-") == 0) {
			    dictionary_url = document.getElementById("ic-arabic-dictionary-url").value;
			}
			/** The text of the word is fetched */
	  	        var word_text = document.getElementById(word_id).innerHTML;
	  	        /** If the word text is not defined then the function returns */
	  	        if (word_text == undefined || word_text == "") return;
	  	        /** The html of the word is removed */
			   word_text = word_text.replace("<span class='highlight-word ic-cursor'>", "");
			   /** The html of the word is removed */
			   word_text = word_text.replace('<span class="highlight-word ic-cursor">', "");
			   /** The html of the word is removed */
			   word_text = word_text.replace("</span>", "");
	  	        /** The html is removed from the word */
	  	        word_text = word_text.replace('<span class="highlight-word ic-cursor">', '');
			/** The current word is added to the dictionary url */
	  	        /** The html is removed from the word */
	  	        word_text = word_text.replace('</span>', '');
	  	        			   /** The non alpha numeric characters are removed */
			   word_text = word_text.replace(/[^a-zA-Z]/g, "");	  	       
	  	        /** The {word} placeholder is replaced in the url */
			dictionary_url = dictionary_url.replace("{word}", word_text);
			/** The currently highlighted word is looked up in online dictionary */
			IC_Navigators.ClickWord(dictionary_url);
		    });
		    
		   document.getElementsByClassName("ic-holy-quran-widget-word")[count].addEventListener("mouseleave", function(){
			/** The currently highlighted word is unhighlighted */
			IC_Navigators.UnHighlightWord();
		    });
            }
	},
	/**
	 * The error in loading holy quran widget is handled
	 *
	 * @param object response the ajax response object
	 */
	DataLoadError: function(response)
	{
            /** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Holy Quran");
	    alert(IC_L10n.data_fetch_alert);				
	},
	/**
	 * The holy quran verses are displayed
	 *
	 * @param response the ajax response object
	 */
	LoadFetchVerseData: function(response)
	{
	    var expiry_date;
		    var current_time = new Date();
		    var expiry_time = (current_time.getTime()*1) + (24*3600*365);
		    current_time.setTime(expiry_time);
		    expiry_date = current_time.toUTCString();
		    /** The HolyQuranNavigator cookie is set */
		    document.cookie = "HolyQuranNavigatorState=" + encodeURIComponent(window.btoa(JSON.stringify(response.state))) + ";expires=" + expiry_date;
		    if(response && response.result=='success') {
		       document.getElementById("ic-holy-quran-navigator-text").outerHTML= response.text;
		        IC_Holy_Quran_Dashboard_Widget.RegisterEventHandlers();
      		        IC_Holy_Quran_Dashboard_Widget.mode = "navigator";
		    }
		    else {
		        alert(IC_L10n.data_fetch_alert);		     
		        /** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Holy Quran");
		    }		 				 
	},
        /**
	 * Fetches verse data from the server	 
	 *
	 * @since    2.0.0
	 */
	FetchVerseData: function(navigator_action)
	{	
	        /** The ajax nonce */
	        var ajax_nonce                   = "";
	        /** If the ajax nonce is defined */
	        if (document.getElementById('ic_ajax_nonce_holy_quran') != undefined) {
 		    /** The ajax nonce. it allows secure ajax calls to WordPress */	
		    ajax_nonce                   = document.getElementById('ic_ajax_nonce_holy_quran').value;
		}
		
		/** The division number */
		var division_number              = 1;
		/** If the division number box is present */
		if(document.getElementById('ic-division-number') !=undefined) {
			division_number          = document.getElementById('ic-division-number').value;
		}
		/** The navigator settings data */
		var navigator_settings           = document.getElementById('ic-quran-settings').innerHTML;
		/** The navigator settings data is decoded */
		navigator_settings           = JSON.parse(window.atob(navigator_settings));
		/** The state of the Holy Quran Widget. it contains the current selected data */
		var navigator_state              = {
			division_number: division_number,
			sura: document.getElementById('ic-sura').value,
			ruku: document.getElementById('ic-ruku').value,	
			action: navigator_action,
			template: "dashboard",
			division: navigator_settings['division'],
			narrator: navigator_settings['narrator'],
			language: navigator_settings['language'],
			ayat: navigator_settings['ayat']
		};
		/** The location of the navigator. it is either frontend or backend */
		var navigator_location = "backend";
		/** If the current url does not include wp-admin, then the navigator is assumed to be displayed on the website frontend */
		if (location.href.indexOf("wp-admin") < 0)
		    navigator_location = "frontend";
		/** The navigator state is converted to string so it can be sent in ajax request */
		var navigator_state              = window.btoa(JSON.stringify(navigator_state));
		/** The parameters for the ajax call */		
		var parameters                   = {			
		    action: "holyqurandashboardwidget",
			plugin_action: "fetch_navigator_data",
			security: ajax_nonce,
			state: navigator_state,
			view: navigator_location,
			plugin: 'IC_HolyQuranDashboardWidget'
		};
		
		/** The overlay div is shown */
	        IC_Navigators.DisplayOverlay(true, "Holy Quran");
	        /** The ajax call is made */					
	        Utilities.MakeAjaxCall('/wp-admin/admin-ajax.php', "POST", parameters, IC_Holy_Quran_Dashboard_Widget.LoadFetchVerseData, IC_Navigators.DataLoadError);
          },
        /**
	 * Displays sura ruku 
	 *
	 * @since    2.0.0
	 */
	LoadDisplaySuraRuku: function(response)
	{
	document.getElementById("ic-holy-quran-navigator-text").outerHTML = response.text;
		        /** The right padding is set to 60px since the data is in 2 columns */
		      var element_list = document.querySelectorAll(".verse-table > tbody > tr > td:nth-child(1)");
		      
		      for (count = 0; count < element_list.length; count++) {
		          element_list[count].style.padding_right = "60px";
		      }
		        var current_url = location.href;
		        /** If the current url has a '#' */
		        if (location.href.indexOf('#') > 0) {
		            var temp_arr = location.href.split("#");
		            /** The current url is set */
		            current_url = temp_arr[0];
		        }
	                /** The page is scrolled to the correct ayat */
		        location.href = current_url + "#holy-quran-translation-" + IC_Holy_Quran_Dashboard_Widget.search_result_id;		            
		        
		        /** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Holy Quran");
		        IC_Holy_Quran_Dashboard_Widget.RegisterEventHandlers();
      		        IC_Holy_Quran_Dashboard_Widget.mode = "navigator";	
	},
        /**
	 * The holy quran sura ruku is displayed
	 *
	 * @param response the ajax response object
	 */
	DisplaySuraRuku: function(parameters)
	{
	    /** The ajax nonce */
	        var ajax_nonce                   = "";
	        /** If the ajax nonce is defined */
	        if (document.getElementById('ic_ajax_nonce_holy_quran') != undefined) {
 		    /** The ajax nonce. it allows secure ajax calls to WordPress */	
		    ajax_nonce                   = document.getElementById('ic_ajax_nonce_holy_quran').value;
		}
	    /** The function parameters are decoded */
	    var sura_information = JSON.parse(window.atob(parameters));	    
	    /** The division name is fetched */
    	    var division_name = document.getElementsByClassName('division-class')[0].innerHTML.toLowerCase();
    	    /** If the division name is empty then it is set to sura */
    	    if (division_name == "") division_name = "sura";
    	    /** The search result id */
    	    IC_Holy_Quran_Dashboard_Widget.search_result_id = sura_information['sura'] + "-" + sura_information['sura_ayat_id'];
    	    /** The location of the navigator. it is either frontend or backend */
            var navigator_location = "backend";
            /** If the current url does not include wp-admin, then the navigator is assumed to be displayed on the website frontend */
	    if (location.href.indexOf("wp-admin") < 0)navigator_location = "frontend";

		/** The navigator settings data */
		var navigator_settings           = document.getElementById('ic-quran-settings').innerHTML;
		/** The navigator settings data is decoded */
		navigator_settings           = JSON.parse(window.atob(navigator_settings));
		/** The state of the Holy Quran Widget. it contains the current selected data */
		var navigator_state              = {
			division_number: sura_information[division_name],
			sura: sura_information['sura'],
			ruku: sura_information['ruku'],	
			action: "current",
                        template: "dashboard",
			division: navigator_settings['division'],
			narrator: navigator_settings['narrator'],
			language: navigator_settings['language'],
			ayat: navigator_settings['ayat']
		};
               /* The navigator state is converted to string so it can be sent in ajax request */
		var navigator_state              = window.btoa(JSON.stringify(navigator_state));
		/** The parameters for the ajax call */		
		var parameters                   = {			
		    action: "holyqurandashboardwidget",
			plugin_action: "fetch_navigator_data",
			security: ajax_nonce,
			state: navigator_state,
			view: navigator_location,
			plugin: 'IC_HolyQuranDashboardWidget'
		};
		/** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(true, "Holy Quran");
	        /** The ajax call is made */					
	        Utilities.MakeAjaxCall('/wp-admin/admin-ajax.php', "POST", parameters, IC_Holy_Quran_Dashboard_Widget.LoadDisplaySuraRuku, IC_Navigators.DataLoadError);		
	},
	/**
	 * The holy quran search results are displayed
	 *
	 * @param object response the ajax response object
	 */
	LoadHolyQuranSearchResults: function(response)
	{
	     /** The search results are displayed */
		        document.getElementsByClassName("verse-table")[0].innerHTML = ("<tr><td>" + response.text + "</td></tr>");
		        /** The right padding is set to 60px since the data is in 2 columns */
		      var element_list = document.querySelectorAll(".verse-table > tbody > tr > td:nth-child(1)");
		      
		      for (count = 0; count < element_list.length; count++) {
		          element_list[count].style.padding_right = "0px";
		      }
		        
		        /** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Holy Quran");
		        IC_Holy_Quran_Dashboard_Widget.mode = "search";

	},
        /**
	 * Searches verse data 
	 *
	 * @since    2.3
	 */
	SearchVerseData: function(page_number)
	{	
	        /** If the search box is empty then an alert message is shown to the user */
	        if (document.getElementById('ic-holy-quran-searchbox').value == "") {
	            /** The alert message is shown */
	            alert(IC_L10n.searchbox_empty_alert);
	            /** The focus is set on the search box */
	            document.getElementById('ic-holy-quran-searchbox').focus();
	            return;
	        }
		/** The ajax nonce */
	        var ajax_nonce                   = "";
	        /** If the ajax nonce is defined */
	        if (document.getElementById('ic_ajax_nonce_holy_quran') != undefined) {
 		    /** The ajax nonce. it allows secure ajax calls to WordPress */	
		    ajax_nonce                   = document.getElementById('ic_ajax_nonce_holy_quran').value;
		}
		/** The page number is saved */
		IC_Holy_Quran_Dashboard_Widget.page_number = page_number;
		/** The search text is saved */
		IC_Holy_Quran_Dashboard_Widget.search_text = document.getElementById('ic-holy-quran-searchbox').value;
		/** The state of the Holy Quran Widget */
		var navigator_state              = {
			page_number: page_number,
			search_text: document.getElementById('ic-holy-quran-searchbox').value,
			action: "search"
		};
		/** The navigator state is converted to string so it can be sent in ajax request */
		var navigator_state              = window.btoa(JSON.stringify(navigator_state));
		/** The location of the navigator. it is either frontend or backend */
                var navigator_location = "backend";
                /** If the current url does not include wp-admin, then the navigator is assumed to be displayed on the website frontend */
	        if (location.href.indexOf("wp-admin") < 0)navigator_location = "frontend";
		/** The parameters for the ajax call */		
		var parameters                   = {			
		    action: "holyqurandashboardwidget",
			plugin_action: "search_verse_data",
			security: ajax_nonce,
			state: navigator_state,
			view: navigator_location,
			plugin: 'IC_HolyQuranDashboardWidget'
		};
		/** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(true, "Holy Quran");
	        /** The ajax call is made */					
	        Utilities.MakeAjaxCall('/wp-admin/admin-ajax.php', "POST", parameters, IC_Holy_Quran_Dashboard_Widget.LoadHolyQuranSearchResults, IC_Navigators.DataLoadError);
    }
};

 window.addEventListener("load", function(){
	 
	 IC_Holy_Quran_Dashboard_Widget.RegisterEventHandlers();
});
