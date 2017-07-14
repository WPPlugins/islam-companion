var IC_Navigators   = {
        /** The current word id */
        current_word_id : "",
        /** The javascript timer id for the current word */
        current_word_timer_id: "",
      	/** Indicates that the navigator has been loaded */
	navigator_loaded                  : false,
        /** The message to be shown to the user when the text is copied */
	text_copied_message : "Text was successfully copied to clipboard",
	/** The error message that is shown to the user */
	error_message : "An error has occurred in the application. Please contact the system administrator",
        /**	 
	 * Used to redirect the user to the dictionary url
	 * If the user's mouse is over a word then the user is redirected to the dictionary page
	 * Otherwise the function returns
	 *
	 * @param string dictionary_url the dictionary url
	 *
	*/
	ClickWord: function(dictionary_url)
	{
	    try {
		   /** If no word is highlighted then the function returns */
		   if (IC_Navigators.current_word_id == "") return;
		   /** The user is redirected to the dictionary page */
		   window.open(dictionary_url);
		}
		catch(err) {
		    alert(IC_Navigators.error_message);
		}		
	},	
        /**	 
	 * Used to highlight the given word
	 * It underlines the given word
	 * If the word is clicked, then the meaning of the word is opened in online dictionary
	 *
	 * @param int word_id the id of the currently highlighted word
	 *
	*/ 
	HighlightWord: function(word_id)
	{
	    try {
		   /** The id of the html element where the mouse is currently positioned */
		   IC_Navigators.current_word_id = word_id;
		   /** The text of the word is fetched */
		   var word_text = document.getElementById(word_id).innerHTML;
		   /** If the word text is already highlighted then the function returns */
		   if (word_text.indexOf("span") >= 0) return;
		   /** The word is underlined */
		   word_text = "<span class='highlight-word ic-cursor'>" + word_text + "</span>";
		   /** The word text is updated */
		   document.getElementById(word_id).innerHTML = word_text;
		}
	    catch(err) {
                alert(IC_Navigators.error_message);
	    }		
	},
       /**	 
	* Used to unhighlight the given word
	* It removed the underline from the given word
        */ 
	UnHighlightWord: function()
	{
	    try {
	           /** The timer is cleared */
		   clearTimeout(IC_Navigators.current_word_timer_id);
		   /** The id of the html element where the mouse is currently positioned */
		   var word_id = IC_Navigators.current_word_id;	   
	           /** If the word id is not set then the function returns */
	           if (word_id == "" || word_id == undefined || document.getElementById(word_id) == null) return;
		   
		   /** The text of the word is fetched */
		   var word_text = document.getElementById(word_id).innerHTML;
		   /** If the word text is not defined then the function returns */
		   if (word_text == undefined) return;
		   /** The html of the word is removed */
		   word_text = word_text.replace("<span class='highlight-word ic-cursor'>", "");
		   /** The html of the word is removed */
		   word_text = word_text.replace('<span class="highlight-word ic-cursor">', "");
		   /** The html of the word is removed */
		   word_text = word_text.replace("</span>", "");
		   /** The word text is updated */
		   document.getElementById(word_id).innerHTML = (word_text);
		   /** The current word id is set to empty */
		   IC_Navigators.current_word_id = "";
		}
             catch(err) {
	         alert(IC_Navigators.error_message);
	     }		
	},
	/**
	 * Used to scroll to the first verse in the navigator
	 * It is called when the scroll top icon is clicked
	 * 
	 * @param string top_verse_id the html element id of the top verse
	 *
	 * @since 2.0.0
        */
	ScrollTop: function(top_verse_id)
	{
	    var current_url = location.href;
            /** If the current url has a '#' */
	    if (location.href.indexOf('#') > 0) {
	        var temp_arr = location.href.split("#");
		/** The current url is set */
		current_url = temp_arr[0];
	    }
	    /** The page is scrolled to the correct ayat */
	    location.href = current_url + "#" + top_verse_id;		
	},	
	 /**
	 * Toggles the more options section
	 *
	 * @param string status [hide~show] used to indicate if the the more options section needs to be hidden or shown
	 * @param string more_options_selector the more options selector. used to select the more options section
	 * @param string more_options_id the more options image id
	 * @param string less_options_id the less options image id
	 * @param string [Hadith~Holy Quran] type the type of data
	 */
	ToggleMoreOptions: function(status, more_options_selector, more_options_id, less_options_id, type)
	{
	    /** If the more options needs to be shown */
	    if (status == "show") {
	        /** The more options section is displayed */
	        document.querySelector(more_options_selector).classList.remove("ic-hidden");
	        /** The more options image is hidden */
	        document.getElementById(more_options_id).classList.add("ic-hidden");	   
	        /** The less options image is shown */
	        document.getElementById(less_options_id).classList.remove("ic-hidden");
	        /** If the type is Holy Quran */
	        if (type == "Holy Quran") {
	            /** The focus is set on the search box */
	            document.getElementById('ic-holy-quran-searchbox').focus();
	             /** If the type is Holy Quran and Holy Quran searchbox text was set */
	        if (IC_Holy_Quran_Dashboard_Widget.search_text) {
  	            /** The search text is set */
  	            document.getElementById('ic-holy-quran-searchbox').value = (IC_Holy_Quran_Dashboard_Widget.search_text);
  	            /** The Holy Quran text is searched */
  	            IC_Holy_Quran_Dashboard_Widget.SearchVerseData(IC_Holy_Quran_Dashboard_Widget.page_number);
  	            
  	        }
	        }
	       
  	        /** If the type is Hadith and Hadith searchbox text was set */
	        else if (type == "Hadith") {
	        /** The focus is set on the search box */
	            document.getElementById('ic-hadith-searchbox').focus();
	            if (IC_Hadith_Dashboard_Widget.search_text) {
  	            /** The search text is set */
  	            document.getElementById('ic-hadith-searchbox').value = (IC_Hadith_Dashboard_Widget.search_text);
  	            /** The Hadith text is searched */
  	            IC_Hadith_Dashboard_Widget.SearchHadithData(IC_Hadith_Dashboard_Widget.page_number);	            
  	        }
  	        }
	        
	    }
	    /** If the more options needs to be hidden */
	    else if (status == "hide") {
    	        /** The more options section is hidden */
	        document.querySelector(more_options_selector).classList.add("ic-hidden");
	        /** The more options image is shown */
	        document.getElementById(more_options_id).classList.add("ic-hidden");
	        /** The less options image is hidden */
	        document.getElementById(less_options_id).classList.add("ic-hidden");
	        if (type == "Holy Quran") {
  	            /** The navigator data is again displayed */
  	            IC_Holy_Quran_Dashboard_Widget.FetchVerseData("current");
  	        }
  	        else {
    	            /** The navigator data is again displayed */
  	            IC_Hadith_Dashboard_Widget.FetchHadithData("current");
  	        }
	    }
	},	
	/**
	 * Gets the text selected by the user	 
	 */
	GetSelectionText: function()
	{
	    var text 			= "";
	    if (window.getSelection)
	        text                    = window.getSelection().toString();
            else if (document.selection && document.selection.type != "Control")
		text                    = document.selection.createRange().text;
				    		
	    return text;
	},
	/**
	 * Copies the given shortcode string to clipboard
	 * 
	 * @param string shortcode_string the shortcode string
	 * @param string type [Holy Quran~Hadith] the type of widget
	 */
	GetShortcode: function(shortcode_string, type)
	{
	    /** The shortcode string is base64 decoded */
            var shortcode_string = decodeURIComponent(window.atob(shortcode_string)).replace(/\+/g, " ");
	    /** Newlines are removed from the shortcode string */
	    shortcode_string     = shortcode_string.replace("\n","");
	    shortcode_string     = shortcode_string.replace("\r","");
	    /** Multiple spaces are replaced with a single space */
	    shortcode_string     = shortcode_string.replace(/\s{2,}/g," ");
	    /** The input box id */
	    var input_box_id = "";
	    /** If the widget is Holy Quran */
	    if (type == "Holy Quran") {
	        /** The input box id is set */
	        input_box_id = "ic_holy_quran_clipboard_text";
	    }
	    else {
	        /** The input box id is set */
	        input_box_id = "ic_hadith_clipboard_text";
	    }
	    /** The input box is made visible */
	    document.querySelector("#" + input_box_id).classList.remove("ic-hidden");
	    /** The value of the shortcode is set inside the input box */
	    document.getElementById(input_box_id).value=(shortcode_string);
	    /** Highlight its content */
	    document.getElementById(input_box_id).select();	       
	    /** Copy the highlighted text */
	    document.execCommand("copy");
            /** The input box is made hidden */
	    document.getElementById(input_box_id).classList.add("ic-hidden");
	    /** Inform the user that the shortcode text has been copied to clipboard */
	    alert(IC_Navigators.text_copied_message);	    
	},
        /**
	 * Copies the given html tag contents to clipboard
	 * 
	 * @param string text the text that needs to be copied
	 * @param string type [Hadith~Holy Quran] the type of text
	 */
	CopyText: function(text, type)
	{
	    /** The text is base64 decoded */
            text = decodeURIComponent(window.atob(text)).replace(/\+/g, " ");
	    /** The input box id */
	    var input_box_id = "";
	    /** If the widget is Holy Quran */
	    if (type == "Holy Quran") {
	        /** The input box id is set */
	        input_box_id = "ic_holy_quran_clipboard_text";
	    }
	    else {
	        /** The input box id is set */
	        input_box_id = "ic_hadith_clipboard_text";
	    }
	    /** The input box is made visible */
	    document.getElementById(input_box_id).classList.remove("ic-hidden");
	    /** The text is set inside the input box */
	    document.getElementById(input_box_id).value=(text);
	    /** Highlight its content */
	    document.getElementById(input_box_id).select();	       
	    /** Copy the highlighted text */
	    document.execCommand("copy");
            /** The input box is made hidden */
	    document.getElementById(input_box_id).classList.add("ic-hidden");
	    /** Inform the user that the text has been copied to clipboard */
	    alert(IC_Navigators.text_copied_message);
	},
	/**
	 * Displays or hides the overlay div
	 *
	 * @param boolean is_display used to indicate if the overlay div should be shown or hidden
	 * @param string [Hadith~Holy Quran] type the type of navigator
	 */
	DisplayOverlay: function(is_display, type)
	{
	    /** The container element */
	    var container = "";
	    /** If the type is Hadith */
	    if (type == "Hadith")
	        container = document.getElementById("ic-hadith-navigator-text");	       
	    /** If the type is Holy Quran */
	    else if (type == "Holy Quran")
	        container = document.getElementById("ic-holy-quran-navigator-text");
	     /** If the type is Admin */
	    else if (type == "Admin")
	        container = document.getElementById("ic_settings_left");
            /** If the overlay div needs to be displayed */
	    if (is_display) {	        	
	        /** If the overlay already exists */
	        if (document.getElementById("ic-navigator-overlay")) {
	            /** The overlay class is hidden */
	            document.getElementById("ic-navigator-overlay").classList.remove("ic-hidden");
	        }
	        else {
		    var container_width  = container.offsetWidth;
		    var container_height = container.offsetHeight;

		    var overlay = document.createElement("div");	
		    overlay.id = "ic-navigator-overlay";
		    overlay.style.backgroundColor = "#FFFFFF";
		    overlay.style.opacity = 0.8;
  		    overlay.style.width = (container_width+"px");
		    overlay.style.height = (screen.height) + "px";
		    overlay.style.position = "absolute";
		    overlay.style.top = (container.offsetTop+"px");
		    overlay.style.left = (container.offsetLeft+"px");
		    if (type != "Admin")overlay.innerHTML = "<div id='loading-data-text'>Loading Data. Please Wait...</div>";
		    else overlay.innerHTML = "<div id='loading-data-text'>Deleting Data. Please Wait...</div>";
		    
		    container.appendChild(overlay);
		}
		/** The navigator is marked as loaded */
	        IC_Navigators.navigator_loaded = false;
	    }
	    else {
	        /** The overlay div is hidden */
	        if (document.getElementById("ic-navigator-overlay")) {      
	            /** The hidden class is added to the overlay div */
	            document.getElementById("ic-navigator-overlay").classList.add("ic-hidden");
	        }
	        /** The navigator is marked as not loaded */
	        IC_Navigators.navigator_loaded = true;
	    }
	}
};
