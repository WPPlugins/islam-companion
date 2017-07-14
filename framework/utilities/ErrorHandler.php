<?php
namespace Framework\Utilities;
/**
 * Used to handle errors and exceptions
 *
 * Contains functions that can be registered as error handler, exception handler and shutdown function
 * The handler functions basically all work in the same way. i.e they log the error message
 * The error logging can be configured using parameters passed to the object constructor
 *
 * @category   Framework
 * @package    Utilities
 * @author     Nadir Latif <nadir@pakjiddat.com>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    1.0.4
 */
final class ErrorHandler
{
    /**
     * The single static instance
     */
    protected static $instance;
    /**
     * Contains the error level. e.g E_WARNING
     */
    private $error_level;
    /**
     * Contains detailed error information
     * Includes error message, error file name, error line number and error context
     */
    private $error_message, $error_file, $error_line, $error_context;
    /**
     * The type of the error. i.e error or exception
     */
    private $type;
    /**
     * The name of the application folder
     */
    private $application_folder;
    /**
     * The original exception object that raised the first exception
     */
    private $first_exception_obj;
    /**
     * The exception object included with the exception
     */
    private $exception_obj;
    /**
     * Used to indicate the application context. e.g if the current application is run from the browser or from the command line
     */
    private $context;
    /**
     * Used to call a custom error handling function. It is an array with 2 keys:
     * object=> object containing the error handling function
     * function=> the error handling function. it takes 2 parameters
     * the error trace generated by the logging class and the error details array
     * the error details array has following keys=>
     *
     */
    private $custom_error_handler;
    /**
     * Used to hold the template folder path
     */
    private $template_folder_path;
    /**
     * Used to indicate if the current application is in development mode or production mode
     * If application is in development mode then the error handler will display complete error message to the user
     * If application is in production mode then the error handler will display plain error message
     */
    private $development_mode;
    /**
     * Used to return a single instance of the class
     *
     * Checks if instance already exists
     * If it does not exist then it is created
     * The instance is returned
     *
     * @param array $parameters it contains the configuration information for the logging class object. it should have following keys:
     * @param array $parameters it contains the configuration information for the logging class object. it should have following keys:
     * email=> the email information used for emailing the error message. It is an array with 2 keys: address => the email address string. headers => the email headers string
     * log_file_name=> the name of the log file to which the error will be logged
     * development_mode=> used to indicate if the application is in development mode or production mode
     * context=> used to indicate if class is being used by a browser application
     * custom_error_handler=> used to specify a custom error handling function. it will be called when there is an error or exception. it is an array with 2 keys
     *  					  first is the object the second is the name of the function that will handle the error
     * 						  this function should exist within the object
     * shutdown_function=> used to specify a custom shutdown function. it will be called when the current script ends. it is an array with 2 keys
     *  					  first is the object the second is the name of the function that will be called
     * 						  this function should exist within the object
     *
     * @return ErrorHandler static::$instance name the single instance of the class is returned
     */
    public static function GetInstance($parameters) 
    {
        if (self::$instance == null) 
        {
            self::$instance = new self($parameters);
        }
        return self::$instance;
    }
    /**
     * Class constructor
     * Used to prevent creating an object of this class outside of the class using new operator
     *
     * Used to implement Singleton class
     * Used to initialize the object variables from the constructor parameters
     *
     * @param array $parameters it contains the configuration information for the logging class object. it should have following keys:
     * application_folder => the name of the application folder. if the error file path does not include the application folder then the error/exception is not logged
     * email=> the email information used for emailing the error message. It is an array with 2 keys: email_address => the email address string. email_header => the email headers string
     * log_file_name=> the name of the log file to which the error will be logged
     * development_mode=> used to indicate if the application is in development mode or production mode
     * context=> used to indicate if class is being used by a browser application
     * custom_error_handler=> used to specify a custom error handling function. it will be called when there is an error or exception. it is an array with 2 keys
     *  					  first is the object the second is the name of the function that will handle the error
     * 						  this function should exist within the object
     * shutdown_function=> used to specify a custom shutdown function. it will be called when the current script ends. it is an array with 2 keys
     *  					  first is the object the second is the name of the function that will be called
     * 						  this function should exist within the object
     */
    protected function __construct($parameters) 
    {
        /** Object properties are set to the constructor parameters */
        $this->custom_error_handler = isset($parameters['custom_error_handler']) ? $parameters['custom_error_handler'] : "";
        $this->type = "";
        $this->application_folder = isset($parameters['application_folder']) ? $parameters['application_folder'] : '';
        $this->development_mode = isset($parameters['development_mode']) ? $parameters['development_mode'] : true;
        $this->context = isset($parameters['context']) ? $parameters['context'] : 'browser';
        /** The path to the templates folder */
        $this->template_folder_path = realpath(__DIR__ . DIRECTORY_SEPARATOR . "templates");
        /** The error handler, exception handler and shutdown handler functions are registered */
        set_error_handler(array(
            $this,
            "ErrorHandler"
        ));
        set_exception_handler(array(
            $this,
            "ExceptionHandler"
        ));
        register_shutdown_function(array(
            $this,
            "ShutdownFunction"
        ));
        /** The custom shutdown function is registered */
        if (isset($parameters['shutdown_function']) && $parameters['shutdown_function'] != "" && !(is_callable($parameters['shutdown_function']))) throw new \Exception("Invalid custom shutdown function");
        else if ($parameters['shutdown_function'] != "") register_shutdown_function($parameters['shutdown_function']);
        /** Ths object properties are initialized */
        $this->error_level = $this->error_message = $this->error_file = $this->error_line = $this->error_context = "N.A";
    }
    /**
     * Error handling function
     *
     * The function can be registered as an error handler using set_error_handler php function
     * The function sets the error variables of the object and logs the error
     *
     * @param int $error_level the error level
     * @param string $error_message the error message
     * @param string $error_file the error file name
     * @param int $error_line the error line number
     * @param array $error_context the error context
     */
    public function ErrorHandler($error_level, $error_message, $error_file, $error_line, $error_context) 
    {
        /** The object error properties are set to the error information */
        $this->error_level = $error_level;
        $this->error_message = $error_message;
        $this->error_file = $error_file;
        $this->error_line = $error_line;
        $this->error_context = $error_context;
        $this->type = "Error";
        /** The error message is logged */
        $this->LogError();
    }
    /**
     * Exception handling function
     *
     * The function can be registered as an exception handler using set_exception_handler php function
     * The function sets the error variables of the object including the object that raised the exception and logs the error
     *
     * @param object $exception_obj the exception object that contains the error information
     */
    public function ExceptionHandler($exception_obj) 
    {
        /** The last exception objects are set to the exception_obj property */
        $this->first_exception_obj = $this->exception_obj = $exception_obj;
        /** The first object that raised the exception is fetched */
        while ($e = $this->first_exception_obj->getPrevious()) $this->first_exception_obj = $e;
        /** The exception properties of the class are set */
        $log_message = "";
        $response = array(
            "result" => "error",
            "data" => $this->first_exception_obj->getMessage()
        );
        $this->exception_obj = $this->first_exception_obj;
        $this->error_level = $this->first_exception_obj->getCode();
        $this->error_message = $this->first_exception_obj->getMessage();
        $this->error_file = $this->first_exception_obj->getFile();
        $this->error_line = $this->first_exception_obj->getLine();
        $this->error_context = $this->first_exception_obj->getTrace();
        $this->type = "Exception";
        /** The exception is logged */
        $this->LogError();
    }
    /**
     * Shutdown function
     *
     * This function can be registered as a shutdown handling function. It can be registered with the register_shutdown_function php function
     * This function is called after the script execution ends
     * If the script has an error or exception that was not handled before then this function can handle the error/exception
     */
    public function ShutdownFunction() 
    {
        /** The last error message is fetched */
        $error = error_get_last();
        /** If there was an error then it is handled using the ErrorHandler function */
        if (isset($error["type"])) $this->ErrorHandler($error["type"], $error["message"], $error["file"], $error["line"], "Fatal error in script");
    }
    /**
     * Get Parameter information
     *
     * Used to get the function parameter information for a stack trace entry
     *
     * @param array $trace the stack trace entry for which the function parameters are required
     * @param string $class_name name of the class that includes the function
     * @param string $function_name name of the function whose parameter information is needed
     *
     * @return string $function_parameter_html html string containing function parameter information
     */
    private function GetFunctionParameters($trace, $class_name, $function_name) 
    {
        /** The error message template file name suffix. If the application is being accessed from web browser then the suffix is set to html. otherwise it is set to plain */
        $error_template_suffix = ($this->context == 'browser') ? "html" : "plain";
        /** The line break string. If the application is being accessed from web browser then the line break is set to <br/>. Otherwise it is set to '\n' */
        $line_break = ($this->context == 'browser') ? "<br/>" : "\n";
        /** The stack trace information. Used to render the function_parameters.html template file */
        $template_parameters = array();
        /** Each function parameter is rendered using function_parameters.html template file */
        for ($count = 0;$count < count($trace['args']);$count++) 
        {
            /** The parameters for a single function */
            $parameters = array();
            /** Gets function parameter value from stack trace */
            $parameter_value = $trace['args'][$count];
            /** If parameter value is an array then it is converted to string */
            if (is_array($parameter_value) || is_object($parameter_value)) $parameter_value = json_encode($parameter_value);
            /** If the parameter value is numeric then it is converted to a string */
            else if (is_numeric($parameter_value)) $parameter_value = strval($parameter_value);
            /** Checks if the given class exists */
            if (class_exists($class_name)) 
            {
                try
                {
                    /** Gets function parameter name from ReflectionParameter class */
                    $parameters_information = new \ReflectionParameter(array(
                        $class_name,
                        $function_name
                    ) , $count);
                    $parameter_name = $parameters_information->getName();
                }
                catch(\Exception $e) 
                {
                    /** If the parameter information could not be fetched then the parameter name is set to N.A */
                    $parameter_name = "N.A";
                }
            }
            else $parameter_name = "N.A";
            if (is_object($parameter_value)) $parameter_value = "Object of class: " . get_class($parameter_value);
            /** Adds the function parameter information to the template parameters array */
            $parameters['param_number'] = ($count + 1);
            $parameters['param_name'] = $parameter_name;
            $parameters['param_value'] = (strlen($parameter_value) > 200) ? wordwrap(base64_encode($parameter_value), 100, $line_break, true) : $parameter_value;
            $template_parameters[] = $parameters;
        }
        /** If the trace contained parameters then the stack trace is rendered using error_message.html template file */
        if (count($template_parameters) > 0) $function_parameter_html = UtilitiesFramework::Factory("template")->RenderTemplateFile($this->template_folder_path . DIRECTORY_SEPARATOR . "function_parameters_" . $error_template_suffix . ".html", $template_parameters);
        else $function_parameter_html = "";
        return $function_parameter_html;
    }
    /**
     * This function is used to format error message text
     *
     * It breaks down the error message stack and displays the error messages in numbered format
     *
     * @param boolean $html_stack_trace used to force use of html template files for generating stack trace
     *
     * @return array $stack_trace_html the complete formatted error message
     */
    private function GetStackTrace($html_stack_trace) 
    {
        /** The error message template file name suffix. If the application is being accessed from web browser then the suffix is set to html. otherwise it is set to plain */
        $error_template_suffix = ($this->context == 'browser' || $html_stack_trace) ? "html" : "plain";
        /** If the custom stack trace is not given, then the stack trace is fetched */
        $stack_trace = debug_backtrace();
        /** The start index of the stack trace */
        $start_index = 0;
        /**
         * If the most recent stack trace function was inside the ErrorHandler class
         * Then the actual stack trace is inside the context property of the current object
         */
        if ($stack_trace[0]['class'] == 'Framework\Utilities\ErrorHandler' && isset($stack_trace[0]['object']->error_context[0])) 
        {
            $start_index = 0;
            $stack_trace = $stack_trace[0]['object']->error_context;           
        }
        /** Otherwise the stack trace starts from the 4th entry */
        else 
        {
            $start_index = 3;
        }
        /** The stack trace html */
        $stack_trace_html = "";
        /** The stack trace information. Used to render the error_message.html template file **/
        $template_parameters = array();
        /*
         * Information of each stack trace is added to an array
         * The first three entries of the stack trace are skipped
         * Since they include the ErrorHandler class functions
        */
        for ($count = $start_index;$count < count($stack_trace);$count++) 
        {
            $trace = $stack_trace[$count];
            /** The parameters for a single function */
            $parameters = array();
            $file_name = (isset($trace['file'])) ? $trace['file'] : "N.A";
            $line = (isset($trace['line'])) ? $trace['line'] : "N.A";
            $function = (isset($trace['function'])) ? $trace['function'] : "N.A";
            $class = (isset($trace['class'])) ? $trace['class'] : "N.A";
            $function_parameters = ($class == "N.A" || !isset($trace['args'])) ? "N.A" : $this->GetFunctionParameters($trace, $class, $function);
            $parameters['function_number'] = ($count - 2);
            $parameters['file_name'] = $file_name;
            $parameters['line_number'] = $line;
            $parameters['function_name'] = $function;
            $parameters['function_parameters'] = $function_parameters;
            $parameters['class_name'] = $class;
            $parameters['counter'] = ($count + 1);
            $template_parameters[] = $parameters;
        }
        /** The stack trace is rendered using stack_trace.html template file */
        if (count($stack_trace) > 0) $stack_trace_html = UtilitiesFramework::Factory("template")->RenderTemplateFile($this->template_folder_path . DIRECTORY_SEPARATOR . "stack_trace_" . $error_template_suffix . ".html", $template_parameters);
        else $stack_trace_html = "";
        return $stack_trace_html;
    }
    /**
     * This function is used to format the given error
     *
     * It uses html template files to format the error
     * It calls the callback object if one is given
     *
     * @param object $exception_obj the exception object that contains the error information
     *
     * @param string $error_type the error type
     * @param int $error_level the error level
     * @param string $error_message the error message
     * @param string $error_file the error file name
     * @param int $error_line the error line number
     * @param array $error_context the error context
     * @param array $custom_error_callback the custom error callback function
     * @param array $custom_stack_trace
     *
     * @return string $error_message the error message
     */
    public function FormatError($error_type, $error_level, $error_message, $error_file, $error_line, $error_context, $custom_error_callback, $custom_stack_trace) 
    {
        /** The error message template file name suffix. If the application is being accessed from web browser then the suffix is set to html. otherwise it is set to plain */
        $error_template_suffix = ($this->context == 'browser') ? "html" : "plain";
        /** If a custom stack trace is not required and the application is being accessed from web browser then the error_message_full_page_html.html file is used */
        if (!is_array($custom_stack_trace) && $error_template_suffix == "html") $error_template_suffix = "full_page_html";
        /** The error log template parameters */
        $template_parameters = array();
        $template_parameters['date'] = date("d-m-Y H:i:s");
        $template_parameters['error_level'] = strval($error_level);
        $template_parameters['error_file'] = $error_file;
        $template_parameters['error_line'] = $error_line;
        $template_parameters['error_message'] = $error_message;
        $template_parameters['stack_trace'] = $this->GetStackTrace(false);
        /** The log message. The error message is rendered using the correct template file */
        $log_message = UtilitiesFramework::Factory("template")->RenderTemplateFile($this->template_folder_path . DIRECTORY_SEPARATOR . "error_message_" . $error_template_suffix . ".html", $template_parameters);
        /** The html stack trace is generated */
        $template_parameters['stack_trace'] = $this->GetStackTrace(true);
        /** The log message. The error message is rendered using the error_message_html.html file */
        $html_log_message = UtilitiesFramework::Factory("template")->RenderTemplateFile($this->template_folder_path . DIRECTORY_SEPARATOR . "error_message_html.html", $template_parameters);        
        /** 
         * The log message is displayed to the browser if the development_mode option is true
         * If development_mode is false then a simple error message is displayed as an alert if the application is browser based
         * If application is not browser based. e.g command line or api application then error message is simply echoed back
         *
         */
        if (!$this->development_mode) 
        {
            /** The name of the template file used to render the error message to the user */
            $template_file_name = $this->template_folder_path . DIRECTORY_SEPARATOR . "production_error_" . $error_template_suffix . ".html";
            /** The template parameters used to render the error template */
            $template_parameters = array(
                "error_message" => "An error has occured in the application. Please contact the system administrator"
            );
            /** The error message that is displayed to the user is rendered */
            $log_message = UtilitiesFramework::Factory("template")->RenderTemplateFile($template_file_name, $template_parameters);
        }
        /** 
         * If a custom error handling function is defined then it is called
         * The log message and error details are given as arguments
         */
        if ($custom_error_callback && is_callable($custom_error_callback)) 
        {
            $error_parameters = array(
                "error_level" => $error_level,
                "error_message" => $error_message,
                "error_file" => $error_file,
                "error_line" => $error_line,
                "error_details" => $html_log_message,
                "error_type" => $error_type
            );
            /** calls the user defined error handler if one is defined */
            call_user_func_array($custom_error_callback, array(
                $log_message,
                $error_parameters
            ));
        }   
        /** If the custom error handler is defined but is not valid then an exception is thrown */
        else if ($custom_error_callback) throw new \Exception("Invalid custom error handler type given");
        /** If the custom error handling function is not defined then the log message is returned */
        
        return $log_message;
    }
    /**
     * Logging function
     *
     * This function is used to log errors
     * The function saves the error to a log file and optionally emails the error to the user
     * The error message is also echoed
     */
    public function LogError() 
    {
        try
        {
            /** If the error file is not given or it is given and the exception occured in a file that is not part of the application, then the function returns */
            if (strpos($this->error_file, $this->application_folder) === false && strpos($this->error_file, "framework") === false) 
            {
                return;
            }
            /** The error message is returned */
            $error_message = $this->FormatError($this->type, $this->error_level, 
                                                $this->error_message, $this->error_file, 
                                                $this->error_line, $this->error_context, 
                                                $this->custom_error_handler,false);
            /** The error message is displayed and program execution ends */
            die($error_message);
        }
        catch(Exception $e) 
        {
            die($e->GetMessage());
        }
    }
}
?>