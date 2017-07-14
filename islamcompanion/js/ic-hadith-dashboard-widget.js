var IC_Hadith_Dashboard_Widget    = {
    /** the overlay object. this object is used to create the grayout affect before an ajax call */
	overlay                           : new Object(),
	/** the html id of the element where the grayout effect is created */
	overlay_div_id                    : "ic-hadith-navigator-text",
	/** The	page number of the search results */
	page_number                       : "1",
	/** The search text */
	search_text                       : "",
	/** The search result id */
	search_result_id                  : "",	
	/**
	 * Used to register the event handlers for the Hadith Dashboard Widget
	 * 
	 * @since 2.0.0 
	 */
	RegisterEventHandlers: function()
	{
	    if (document.getElementById("ic-hadith-book") != undefined) {
		    document.getElementById("ic-hadith-book").addEventListener("change", function(){
		     	IC_Hadith_Dashboard_Widget.FetchHadithData("hadith_book_box");
		    });
	    }
	    
	    if (document.getElementById("ic-hadith-title") != undefined) {
	    	document.getElementById("ic-hadith-title").addEventListener("change", function(){
	     		IC_Hadith_Dashboard_Widget.FetchHadithData("hadith_title_box");
  	        });
	    }
	    
	    if (document.getElementById("ic-hadith-source") != undefined) {
		    document.getElementById("ic-hadith-source").addEventListener("change", function(){
		     	IC_Hadith_Dashboard_Widget.FetchHadithData("hadith_source_box");
		    });
	    }
	    
	    if (document.getElementById("ic-hadith-next") != undefined) {
		    document.getElementById("ic-hadith-next").addEventListener("click", function(){
		     	IC_Hadith_Dashboard_Widget.FetchHadithData("next");
		    });
	    }
	    
	    if (document.getElementById("ic-hadith-prev") != undefined) {
		    document.getElementById("ic-hadith-prev").addEventListener("click", function(){
		     	IC_Hadith_Dashboard_Widget.FetchHadithData("previous");
		    });
	    }
	    
	    if (document.getElementById("ic-hadith-searchbox") != undefined) {
		    document.getElementById("ic-hadith-searchbox").addEventListener("keydown", function(event){
		     	/** If the event key was pressed */
		     	if (event.keyCode == 13) {
		     	    IC_Hadith_Dashboard_Widget.SearchHadithData("1");
		     	}
		    });
	    }
	    
	    var word_list = document.getElementsByClassName("ic-hadith-widget-word");
	    
	    for (count = 0; count < word_list.length; count++) {
		    document.getElementsByClassName("ic-hadith-widget-word")[count].addEventListener("mouseenter", function(){
			/** The word id */
			var word_id = this.id;   
		     	/** The word is highlighted */
		     	IC_Navigators.HighlightWord(word_id);
		    });
		    
		    document.getElementsByClassName("ic-hadith-widget-word")[count].addEventListener("click", function(){
			/** The id of the html element where the mouse is currently positioned */
			var word_id = IC_Navigators.current_word_id
			/** The dictionary url */
			var dictionary_url = document.getElementById("ic-hadith-dictionary_url").value;
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
			   /** The non alpha numeric characters are removed */
			   word_text = word_text.replace(/[^a-zA-Z]/g, "");
			/** The current word is added to the dictionary url */
			dictionary_url = dictionary_url.replace("{word}", word_text);
			/** The currently highlighted word is looked up in online dictionary */
			IC_Navigators.ClickWord(dictionary_url);
		    });
		    
		    document.getElementsByClassName("ic-hadith-widget-word")[count].addEventListener("mouseleave", function(){
			/** The currently highlighted word is unhighlighted */
			IC_Navigators.UnHighlightWord();
		    });
            }
	},
	/**
	 * The hadith data is loaded
	 *
	 * @param object response the ajax response object
	 */
	LoadDisplayHadithChapter: function(response)
	{
	    var expiry_date;
            var current_time = new Date();
	    var expiry_time = (current_time.getTime()*1) + (24*3600*365);
	    current_time.setTime(expiry_time);
	    expiry_date = current_time.toUTCString();
	    /** The HolyQuranNavigator cookie is set */
	    document.cookie = "HadithNavigatorState=" + encodeURIComponent(window.btoa(JSON.stringify(response.state))) + ";expires=" + expiry_date;
	    if(response&&response.result=='success') {
		        document.getElementById("ic-hadith-navigator-text").outerHTML = response.text;
		        var current_url = location.href;
		        /** If the current url has a '#' */
		        if (location.href.indexOf('#') > 0) {
		            var temp_arr = location.href.split("#");
		            /** The current url is set */
		            current_url = temp_arr[0];
		        }
	                /** The page is scrolled to the correct ayat */
		        location.href = current_url + "#" + IC_Hadith_Dashboard_Widget.search_result_id;		
		        /** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Hadith");
		        IC_Hadith_Dashboard_Widget.RegisterEventHandlers();
		    }
		    else {
		        alert(IC_L10n.data_fetch_alert);		     
		        /** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Hadith");
		    }			
	},
	/**
	 * The hadith data is loaded
	 *
	 * @param object response the ajax response object
	 */
	LoadFetchHadithData: function(response)
	{
	    var expiry_date;
            var current_time = new Date();
	    var expiry_time = (current_time.getTime()*1) + (24*3600*365);
	    current_time.setTime(expiry_time);
	    expiry_date = current_time.toUTCString();
	    /** The HolyQuranNavigator cookie is set */
	    document.cookie = "HadithNavigatorState=" + encodeURIComponent(window.btoa(JSON.stringify(response.state))) + ";expires=" + expiry_date;			
	    if(response && response.result=='success') {
                document.getElementById("ic-hadith-navigator-text").outerHTML = response.text;
		/** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Hadith");
		IC_Hadith_Dashboard_Widget.RegisterEventHandlers();
	    }
	    else {
	        alert(IC_L10n.data_fetch_alert);		     
		/** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Hadith");
	    }		 			
	},
	/**
	 * The error in loading hadith widget is handled
	 *
	 * @param object response the ajax response object
	 */
	DataLoadError: function(response)
	{
            /** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Hadith");
	    alert(IC_L10n.data_fetch_alert);				
	},
    /**
	 * Fetches verse data from the server	 
	 *
	 * @since    2.0.0
	 */
	FetchHadithData: function(navigator_action)
	{	
		/** The ajax nonce */
	        var ajax_nonce                   = "";
	        /** If the ajax nonce is defined */
	        if (document.getElementById('ic_ajax_nonce_hadith') != undefined) {
 		    /** The ajax nonce. it allows secure ajax calls to WordPress */	
		    ajax_nonce                   = document.getElementById('ic_ajax_nonce_hadith').value;
		}
		/** The navigator settings data */
		var navigator_settings           = document.getElementById('ic-hadith-settings').innerHTML;
		/** The navigator settings data is decoded */
		navigator_settings           = JSON.parse(window.atob(navigator_settings));
		/** The state of the Hadith Widget. it contains the current selected data */
		var navigator_state              = {
			hadith_book: document.getElementById('ic-hadith-book').value,		
			hadith_title: document.getElementById('ic-hadith-title').value,					
			hadith_source: document.getElementById('ic-hadith-source').value,
			hadith_language: navigator_settings['hadith_language'],
			template: "dashboard",
			action: navigator_action
		};
		/** The location of the navigator. it is either frontend or backend */
                var navigator_location = "backend";
                /** If the current url does not include wp-admin, then the navigator is assumed to be displayed on the website frontend */
	        if (location.href.indexOf("wp-admin") < 0)navigator_location = "frontend";
	        /** The navigator state is converted to string so it can be sent in ajax request */
		var navigator_state              = window.btoa(JSON.stringify(navigator_state));
		/** The parameters for the ajax call */		
		var parameters                   = {			
		    action: "hadithdashboardwidget",
			plugin_action: "fetch_hadith_data",
			security: ajax_nonce,
			state: navigator_state,
			view: navigator_location,
			plugin: 'IC_HadithDashboardWidget'
		};	
		/** The overlay div is shown */
	        IC_Navigators.DisplayOverlay(true, "Hadith");
	        /** The ajax call is made */					
	        Utilities.MakeAjaxCall('/wp-admin/admin-ajax.php', "POST", parameters, IC_Hadith_Dashboard_Widget.LoadFetchHadithData, IC_Navigators.DataLoadError);	     		
    },
     /**
	 * The hadith chapter is displayed
	 *
	 * @param string parameters the encoded parameters
	 */
	DisplayHadithChapter: function(parameters)
	{
	    /** The ajax nonce */
	        var ajax_nonce                   = "";
	        /** If the ajax nonce is defined */
	        if (document.getElementById('ic_ajax_nonce_hadith') != undefined) {
 		    /** The ajax nonce. it allows secure ajax calls to WordPress */	
		    ajax_nonce                   = document.getElementById('ic_ajax_nonce_hadith').value;
		}
	    /** The function parameters are decoded */
	    var hadith_information = JSON.parse(window.atob(parameters));
    	    /** The search result id */
    	    IC_Hadith_Dashboard_Widget.search_result_id = "hadith-" + hadith_information['hadith_number'];
    	    /** The navigator settings data */
		var navigator_settings           = document.getElementById('ic-hadith-settings').innerHTML;
		/** The navigator settings data is decoded */
		navigator_settings           = JSON.parse(window.atob(navigator_settings));
		/** The location of the navigator. it is either frontend or backend */
                var navigator_location = "backend";
                /** If the current url does not include wp-admin, then the navigator is assumed to be displayed on the website frontend */
	        if (location.href.indexOf("wp-admin") < 0)navigator_location = "frontend";
	    /** The state of the Holy Quran Widget. it contains the current selected data */
		var navigator_state              = {
			hadith_book: hadith_information['book'],
			hadith_title: hadith_information['title'],		
			hadith_source: hadith_information['source'],	
			hadith_language: navigator_settings['hadith_language'],
			template: "dashboard",
			action: "current"				
		};
		/** The navigator state is converted to string so it can be sent in ajax request */
		var navigator_state              = window.btoa(JSON.stringify(navigator_state));
		/** The parameters for the ajax call */		
		var parameters                   = {			
		     action: "hadithdashboardwidget",
			plugin_action: "fetch_hadith_data",
			security: ajax_nonce,
			state: navigator_state,			
			plugin: 'IC_HadithDashboardWidget',
			view: navigator_location
		};	
		/** The overlay div is shown */
	        IC_Navigators.DisplayOverlay(true, "Hadith");
	        /** The ajax call is made */					
	        Utilities.MakeAjaxCall('/wp-admin/admin-ajax.php', "POST", parameters, IC_Hadith_Dashboard_Widget.LoadDisplayHadithChapter, IC_Navigators.DataLoadError);	        		
	},
	 /**
	 * The hadith chapter is displayed
	 *
	 * @param object response the ajax response object
	 */
	LoadHadithSearchResults: function(response)
	{
	    /** The search results are displayed */
	    document.getElementsByClassName("hadith-table")[0].innerHTML = ("<tr><td>" + response.text + "</td></tr>");
	    /** The overlay div is hidden */
	        IC_Navigators.DisplayOverlay(false, "Hadith");
	    //IC_Hadith_Dashboard_Widget.RegisterEventHandlers();
	},
    /**
	 * Searches hadith data 
	 *
	 * @since    2.3
	 */
	SearchHadithData: function(page_number)
	{	
	        /** If the search box is empty then an alert message is shown to the user */
	        if (document.getElementById('ic-hadith-searchbox').value == "") {
	            /** The alert message is shown */
	            alert(IC_L10n.searchbox_empty_alert);
	            /** The focus is set on the search box */
	            document.getElementById('ic-hadith-searchbox').focus();
	            return;
	        }
		/** The ajax nonce */
	        var ajax_nonce                   = "";
	        /** If the ajax nonce is defined */
	        if (document.getElementById('ic_ajax_nonce_hadith') != undefined) {
 		    /** The ajax nonce. it allows secure ajax calls to WordPress */	
		    ajax_nonce                   = document.getElementById('ic_ajax_nonce_hadith').value;
		}
		/** The page number is saved */
		IC_Hadith_Dashboard_Widget.page_number = page_number;
		/** The search text is saved */
		IC_Hadith_Dashboard_Widget.search_text = document.getElementById('ic-hadith-searchbox').value;
		/** The location of the navigator. it is either frontend or backend */
                var navigator_location = "backend";
                /** If the current url does not include wp-admin, then the navigator is assumed to be displayed on the website frontend */
	        if (location.href.indexOf("wp-admin") < 0)navigator_location = "frontend";
	        /** The navigator settings data */
		var navigator_settings           = document.getElementById('ic-hadith-settings').innerHTML;
		/** The navigator settings data is decoded */
		navigator_settings           = JSON.parse(window.atob(navigator_settings));
		/** The state of the Hadith Widget */
		var navigator_state              = {
			page_number: page_number,
			search_text: document.getElementById('ic-hadith-searchbox').value,
			hadith_book: "",
			hadith_title: "",
			hadith_source: "",
			hadith_language: navigator_settings['hadith_language'],
			action: "search"
		};
		/** The navigator state is converted to string so it can be sent in ajax request */
		var navigator_state              = window.btoa(JSON.stringify(navigator_state));
		/** The parameters for the ajax call */		
		var parameters                   = {			
		    action: "hadithdashboardwidget",
			plugin_action: "search_hadith_data",
			security: ajax_nonce,
			state: navigator_state,
			view: navigator_location,
			plugin: 'IC_HadithDashboardWidget'
		};
		/** The overlay div is shown */
	        IC_Navigators.DisplayOverlay(true, "Hadith");
	        /** The ajax call is made */					
	        Utilities.MakeAjaxCall('/wp-admin/admin-ajax.php', "POST", parameters, IC_Hadith_Dashboard_Widget.LoadHadithSearchResults, IC_Navigators.DataLoadError);
	   }	        		
};

	 window.addEventListener("load", function(){
	 
	 IC_Hadith_Dashboard_Widget.RegisterEventHandlers();
});
