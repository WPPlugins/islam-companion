var IC_Dashboard_Widget    = {
        /** The current word id */
        current_word_id : "",
        /** The javascript timer id for the current word */
        current_word_timer_id: "",
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
		   if (IC_Dashboard_Widget.current_word_id == "") return;
		   /** The user is redirected to the dictionary page */
		   window.open(dictionary_url);
		}
		catch(err) {
		    alert(IC_L10n.general_error);
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
		   IC_Dashboard_Widget.current_word_id = word_id;
		   /** The text of the word is fetched */
		   var word_text = document.getElementById(word_id).html;
		   /** The word is underlined */
		   word_text = "<span class='highlight-word ic-cursor'>" + word_text + "</span>";
		   /** The word text is updated */
		   document.getElementById(word_id).html(word_text);
		}
		catch(err) {
		    alert(IC_L10n.general_error);
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
		   clearTimeout(IC_Dashboard_Widget.current_word_timer_id);
		   /** The id of the html element where the mouse is currently positioned */
		   var word_id = IC_Dashboard_Widget.current_word_id;	   
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
		   document.getElementById(word_id).innerHTML = word_text;
		   /** The current word id is set to empty */
		   IC_Dashboard_Widget.current_word_id = "";
		}
		catch(err) {
		    alert(IC_L10n.general_error);
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
	    var text 						= "";
		if (window.getSelection)
	        text                        = window.getSelection().toString();
		else if (document.selection && document.selection.type != "Control")
		    text                        = document.selection.createRange().text;
				    		
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
	    document.getElementById(input_box_id).classList.remove("ic-hidden");
	    /** The value of the shortcode is set inside the input box */
	    document.getElementById(input_box_id).value = shortcode_string;
	    /** Highlight its content */
	    document.getElementById(input_box_id).select();	       
	    /** Copy the highlighted text */
	    document.execCommand("copy");
            /** The input box is made hidden */
	    document.getElementById(input_box_id).classList.add("ic-hidden");
	    /** Inform the user that the shortcode text has been copied to clipboard */
	    alert(IC_L10n.shortcode_copied_alert);	    
	},
        /**
	 * Copies the given html tag contents to clipboard
	 * 
	 * @param string text the text that needs to be copied
	 * @param string type [hadith~verse] the type of text
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
	    document.getElementById(input_box_id).value = text;
	    /** Highlight its content */
	    document.getElementById(input_box_id).select();	       
	    /** Copy the highlighted text */
	    document.execCommand("copy");
            /** The input box is made hidden */
	    document.getElementById(input_box_id).classList.add("ic-hidden");
	    /** Inform the user that the text has been copied to clipboard */
	    if (type == "Hadith") alert(IC_L10n.hadith_copied_alert);
	    else alert(IC_L10n.ayat_copied_alert);
	}			
};
