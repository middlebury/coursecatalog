<?php

/**
 * @since 10/10/07
 *
 * @copyright Copyright &copy; 2007, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HarmoniErrorHandler.class.php,v 1.20 2008/04/18 14:58:26 adamfranco Exp $
 */

// E_RECOVERABLE_ERROR is not defined in PHP 5.1
if (!defined('E_RECOVERABLE_ERROR')) {
    define('E_RECOVERABLE_ERROR', 4096);
}

/**
 * This is an error handler class that can display and log errors and exceptions.
 *
 * The HarmoniErrorHandler's execution is primarily controlled by the state of the
 * 'error_reporting' and 'display_errors' directives. Any errors that match the current
 * error_reporting level and all uncaught Exceptions will be processed by the
 * error handler and logged. If the display_errors directive is set to 'On', then
 * these errors and exceptions will also be printed to the screen, otherwise they
 * will be only logged.
 *
 * In addition to the behavior devined by the 'error_reporting' and 'display_errors' directives,
 * the HarmoniErrorHandler also allows setting of which error_levels are fatal, causing
 * the execution to halt. This is set with the $errorHandler->fatalErrors() method
 * which has the same parameter syntax as the error_reporting() function.
 *
 * @since 10/10/07
 *
 * @copyright Copyright &copy; 2007, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HarmoniErrorHandler.class.php,v 1.20 2008/04/18 14:58:26 adamfranco Exp $
 */
class harmoni_ErrorHandler
{
    /**
     * @var object ;
     *
     * @since 10/10/07
     *
     * @static
     */
    private static $instance;

    /**
     * This class implements the Singleton pattern. There is only ever
     * one instance of the this class and it is accessed only via the
     * ClassName::instance() method.
     *
     * @return object
     *
     * @since 5/26/05
     *
     * @static
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @var array;
     *
     * @since 10/10/07
     */
    private $errorTypes;

    /**
     * @var int; The bitwise integer that determines whether or not
     * to halt execution when an error occurs
     *
     * @since 10/17/07
     */
    private $fatalErrors;

    /**
     * @var int; The bitwise integer that determines whether or not
     * to halt execution when an error occurs
     *
     * @since 10/17/07
     */
    private $defaultFatalErrors;

    /**
     * @var array; Items to be filtered out of backtraces and logs
     *
     * @since 4/9/08
     */
    private $privateRequestItems;

    /**
     * Constructor.
     *
     * @return void
     *
     * @since 10/10/07
     */
    private function __construct()
    {
        $this->errorTypes = [
            \E_ERROR => 'Error',
            \E_WARNING => 'Warning',
            \E_PARSE => 'Parsing Error',
            \E_NOTICE => 'Notice',
            \E_CORE_ERROR => 'Core Error',
            \E_CORE_WARNING => 'Core Warning',
            \E_COMPILE_ERROR => 'Compile Error',
            \E_COMPILE_WARNING => 'Compile Warning',
            \E_USER_ERROR => 'User Error',
            \E_USER_WARNING => 'User Warning',
            \E_USER_NOTICE => 'User Notice',
            \E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
            \E_STRICT => 'Runtime Notice',
        ];

        // Added in PHP 5.3
        if (defined('E_DEPRECATED')) {
            $this->errorTypes[\E_DEPRECATED] = 'Deprecated Warning';
        }
        if (defined('E_USER_DEPRECATED')) {
            $this->errorTypes[\E_USER_DEPRECATED] = 'User Deprecated Warning';
        }
        $this->defaultFatalErrors = (\E_ERROR | \E_PARSE | \E_CORE_ERROR | \E_COMPILE_ERROR | \E_USER_ERROR | \E_RECOVERABLE_ERROR);
        $this->fatalErrors = $this->defaultFatalErrors;

        $this->privateRequestItems = [];
    }

    /**
     * Make particular error types fatal. Syntax is the same as error_reporting().
     *
     * @param optional int $type A integer bitmap like the error_reporting levels
     *
     * @return int if no argument is passed the current fatal errors will be returned
     *
     * @since 10/10/07
     */
    public function fatalErrors()
    {
        if (!func_num_args()) {
            return $this->fatalErrors;
        }

        $args = func_get_args();
        $level = $args[0];
        if (!is_int($level) || func_num_args() > 1) {
            throw new NullArgumentException('You must specify an integer error level. Should be one or a bitwise combination of E_ERROR, E_WARNING, E_PARSE, E_NOTICE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE, E_RECOVERABLE_ERROR, E_STRICT.');
        }

        $this->fatalErrors = $level;
    }

    /**
     * Answer the default fatal error level.
     *
     * @return int
     *
     * @since 10/17/07
     */
    public function getDefaultFatalErrorLevel()
    {
        return $this->defaultFatalErrors;
    }

    /**
     * Handle an error.
     *
     * @param int    $errorType
     * @param string $errorMessage
     *
     * @return void
     *
     * @since 10/10/07
     *
     * @static
     */
    public static function handleError($errorType, $errorMessage)
    {
        // do a bitwise comparison of the error level to the current error_reporting level
        // and do not print or log if it doesn't match.
        if (!($errorType & error_reporting())) {
            // Check if the error level is fatal continue and if not.
            $handler = self::instance();
            if (!($errorType & $handler->fatalErrors)) {
                return;
            }
            // Die if the error is fatal.
            else {
                exit;
            }
        }

        $backtrace = debug_backtrace();
        // 		$backtrace = array_shift(debug_backtrace());

        // Remove the message from the error-handler call from the backtrace
        $backtrace[0]['function'] = '';
        $backtrace[0]['class'] = '';
        $backtrace[0]['type'] = '';
        $backtrace[0]['args'] = [];

        $errorHandler = self::instance();
        $errorHandler->completeHandlingError($errorType, $errorMessage, $backtrace);
    }

    /**
     * Handle an error.
     *
     * @param int    $errorType
     * @param string $errorMessage
     *
     * @return void
     *
     * @since 10/10/07
     */
    private function completeHandlingError($errorType, $errorMessage, array $backtrace)
    {
        // Only print Errors to the screen if the display_errors directive instructs
        // us to do so.
        if (true === ini_get('display_errors') || 'On' === ini_get('display_errors')
            || 'stdout' === ini_get('display_errors') || '1' === ini_get('display_errors')) {
            $this->printError($errorType, $errorMessage, $backtrace);
        }

        // Log the error.
        $this->logError($errorType, $errorMessage, $backtrace);

        // Exit if the error is fatal
        // do a bitwise comparison of the error level to the current fatalErrors level
        if (!($errorType & $this->fatalErrors)) {
            return;
        } else {
            exit;
        }
    }

    /**
     * Handle an Exception.
     *
     * @param Exception $exception
     *
     * @return void
     *
     * @since 10/10/07
     *
     * @static
     */
    public static function handleException(Throwable $exception)
    {
        $priority = 'Uncaught Exception';

        if (method_exists($exception, 'getType') && $exception->getType()) {
            $type = $exception->getType();
        } else {
            $type = $exception::class;
        }

        // Only print Exceptions to the screen if the display_errors directive instructs
        // us to do so.
        if (true === ini_get('display_errors') || 'on' === strtolower(ini_get('display_errors'))
            || 'stdout' === ini_get('display_errors') || '1' === ini_get('display_errors')) {
            self::printException($exception);
        }

        $trace = $exception->getTrace();
        array_unshift($trace, [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'function' => '',
        ]);

        // Log the Exception
        self::logMessage($priority, $exception->getMessage(), $trace, 'Harmoni', $type);
    }

    /**
     * Print out an exception.
     *
     * @param Exception $exception
     *
     * @return void
     *
     * @since 1/27/09
     *
     * @static
     */
    public static function printException(Throwable $exception, $priority = 'Uncaught Exception')
    {
        if (method_exists($exception, 'getType') && $exception->getType()) {
            $type = $exception->getType();
        } else {
            $type = $exception::class;
        }

        $trace = $exception->getTrace();
        array_unshift($trace, [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'function' => '',
        ]);

        if (ini_get('html_errors')) {
            if (method_exists($exception, 'getHtmlMessage')) {
                self::printHtmlMessage($priority.' of type', $type, $exception->getHtmlMessage(), $trace, $exception->getCode());
            } else {
                self::printMessage($priority.' of type', $type, $exception->getMessage(), $trace, $exception->getCode());
            }
        } else {
            self::printPlainTextMessage($priority.' of type', $type, $exception->getMessage(), $trace, $exception->getCode());
        }

        if (method_exists($exception, 'getPrevious') && is_object($exception->getPrevious())) {
            self::printException($exception->getPrevious(), 'Caused by Exception');
        }
    }

    /**
     * Print out an error message.
     *
     * @param int    $errorType
     * @param string $errorMessage
     *
     * @return void
     *
     * @since 10/10/07
     */
    private function printError($errorType, $errorMessage, array $backtrace)
    {
        if (ini_get('html_errors')) {
            self::printMessage('Error', $this->errorTypes[$errorType], $errorMessage, $backtrace, $errorType);
        } else {
            self::printPlainTextMessage('Error', $this->errorTypes[$errorType], $errorMessage, $backtrace, $errorType);
        }
    }

    /**
     * Print out an error or exception message.
     *
     * @param string $errorOrException a string describing whether this was an error or an uncaught exception
     * @param string $type             The type of error or exception that occurred
     * @param string $message          a message
     * @param optional int $code
     *
     * @return void
     *
     * @since 10/10/07
     */
    public static function printMessage($errorOrException, $type, $message, array $backtrace, $code = null)
    {
        self::printHtmlMessage($errorOrException, $type, htmlentities($message), $backtrace, $code);
    }

    /**
     * Print out an error or exception message.
     *
     * @param string $errorOrException a string describing whether this was an error or an uncaught exception
     * @param string $type             The type of error or exception that occurred
     * @param string $message          a message
     * @param optional int $code
     *
     * @return void
     *
     * @since 04/18/08
     */
    public static function printHtmlMessage($errorOrException, $type, $message, array $backtrace, $code = null)
    {
        echo "\n<div style='background-color: #FAA; border: 2px dotted #F00; padding: 10px;'><strong>".$errorOrException.'</strong>: ';
        echo "\n\t<div style='padding-left: 20px; font-style: italic;'>".$type;
        if (null !== $code) {
            echo ' ('.(string) $code.')';
        }
        echo '</div>';
        echo 'with message ';
        echo "\n\t<div style='padding-left: 20px; font-style: italic;'>".$message.'</div>';
        echo "\n\tin";
        echo "\n\t<div style='padding-left: 20px;'>";
        self::printDebugBacktrace($backtrace);
        echo "\n\t</div>";
        echo "\n</div>";
    }

    /**
     * Print out an error or exception message.
     *
     * @param string $errorOrException a string describing whether this was an error or an uncaught exception
     * @param string $type             The type of error or exception that occurred
     * @param string $message          a message
     * @param optional int $code
     *
     * @return void
     *
     * @since 10/10/07
     */
    public static function printPlainTextMessage($errorOrException, $type, $message, array $backtrace, $code = null)
    {
        echo "\n*****************************************************************************";
        echo "\n* ".$errorOrException.': ';
        echo "\n*\t".$type;
        if (null !== $code) {
            echo ' ('.(string) $code.')';
        }
        echo "\n* with message ";
        echo "\n*\t".$message;
        echo "\n* in";
        echo "\n*";
        self::printPlainTextDebugBacktrace($backtrace);
        echo "\n*****************************************************************************\n";
    }

    /**
     * Log an error with the Logging OSID implementation.
     *
     * @param string $errorType
     * @param string $errorMessage
     *
     * @return void
     *
     * @since 10/10/07
     */
    private function logError($errorType, $errorMessage, array $backtrace)
    {
        self::logMessage($this->errorTypes[$errorType], $errorMessage, $backtrace);
    }

    /**
     * Log an Exception.
     *
     * @param optional string $logName Defaults to the Harmoni log
     *
     * @return void
     *
     * @since 10/24/07
     */
    public static function logException(Exception $exception, $logName = 'Harmoni')
    {
        if (method_exists($exception, 'getType') && $exception->getType()) {
            $type = $exception->getType();
        } else {
            $type = $exception::class;
        }

        $trace = $exception->getTrace();
        array_unshift($trace, [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'function' => '',
        ]);

        self::logMessage('Exception', $exception->getMessage(), $trace, $logName, $type);
    }

    /**
     * Log an error or exception with the Logging OSID implemenation.
     *
     * @param string $type    The type of error or exception that occurred
     * @param string $message a message
     * @param optional string $logName The name of the log to write to
     * @param optional string $category A category for the error or exception
     *
     * @return void
     *
     * @since 10/10/07
     *
     * @static
     */
    public static function logMessage($type, $message, array $backtrace, $logName = 'Harmoni', $category = null)
    {
        /*********************************************************
         * Log the error in the default system log if the log_errors
         * directive is on.
         *********************************************************/
        if (true === ini_get('log_errors') || 'On' === ini_get('log_errors')
            || '1' === ini_get('log_errors')) {
            error_log('PHP '.$type.': '.strip_tags($message).' in '.$backtrace[0]['file'].' on line '.$backtrace[0]['line']);
        }

        /*********************************************************
         * Log the error using the Logging OSID if available.
         *********************************************************/
        // If we have an error in the error handler or the logging system,
        // don't infinitely loop trying to log the error of the error....
        $testBacktrace = debug_backtrace();
        for ($i = 1; $i < count($testBacktrace); ++$i) {
            if (isset($testBacktrace[$i]['function'])
                && 'logMessage' == strtolower($testBacktrace[$i]['function'])) {
                return;
            }
        }
    }

    /**
     * This method will strip out the values of request parameters deemed to be private.
     * By default, and parameter key that contains the string 'password' will be hidden.
     *
     * @return array
     *
     * @since 4/9/08
     *
     * @static
     */
    private static function stripPrivate(array $requestParams)
    {
        $filtered = [];
        $instance = self::instance();
        foreach ($requestParams as $key => $val) {
            if (preg_match('/password/i', $key)) {
                $filtered[$key] = 'VALUE_FILTERED_IN_LOG';
            } elseif (in_array($key, $instance->privateRequestItems)) {
                $filtered[$key] = 'VALUE_FILTERED_IN_LOG';
            } else {
                $filtered[$key] = $val;
            }
        }

        return $filtered;
    }

    /**
     * Add a request key to strip from logs.
     *
     * @param string $key
     *
     * @return void
     *
     * @since 4/9/08
     */
    public function addPrivateRequestKey($key)
    {
        $this->privateRequestItems[] = $key;
    }

    /**
     * Prints a debug_backtrace() array in a pretty HTML way...
     *
     * @param optional array $trace The array. If null, a current backtrace is used.
     * @param optional boolean $return If true will return the HTML instead of printing it
     *
     * @return void
     */
    public static function printDebugBacktrace($trace = null, $return = false)
    {
        if (is_array($trace)) {
            $traceArray = $trace;
        } else {
            $traceArray = debug_backtrace();
        }

        if ($return) {
            ob_start();
        }

        echo "\n\n<table border='1'>";
        echo "\n\t<thead>";
        echo "\n\t\t<tr>";
        echo "\n\t\t\t<th>#</th>";
        echo "\n\t\t\t<th>File</th>";
        echo "\n\t\t\t<th>Line</th>";
        echo "\n\t\t\t<th>Call</th>";
        echo "\n\t\t</tr>";
        echo "\n\t</thead>";
        echo "\n\t<tbody>";
        if (is_array($traceArray)) {
            foreach ($traceArray as $i => $trace) {
                /* each $traceArray element represents a step in the call hiearchy. Print them from bottom up. */
                if (isset($trace['file'])) {
                    $filePath = $trace['file'];
                    $file = basename($trace['file']);
                } else {
                    $filePath = '';
                    $file = '';
                }

                if (isset($trace['line'])) {
                    $line = $trace['line'];
                } else {
                    $line = '';
                }

                $function = $trace['function'];
                $class = $trace['class'] ?? '';
                $type = $trace['type'] ?? '';
                if (isset($trace['args'])) {
                    $args = harmoni_ArgumentRenderer::renderManyArguments($trace['args'], false, false);
                } else {
                    $args = '';
                }

                echo "\n\t\t<tr>";
                echo "\n\t\t\t<td>$i</td>";
                echo "\n\t\t\t<td title=\"".htmlentities($filePath).'">'.htmlentities($file).'</td>';
                echo "\n\t\t\t<td>$line</td>";
                echo "\n\t\t\t<td style='font-family: monospace; white-space: nowrap'>";
                if ($class || $type || $function || $args) {
                    echo htmlentities($class.$type.$function.'('.$args.');');
                }
                echo '</td>';
                echo "\n\t\t</tr>";
            }
        }
        echo "\n\t</tbody>";
        echo "\n</table>";

        if ($return) {
            return ob_get_clean();
        }
    }

    /**
     * Prints a debug_backtrace() array in a pretty plain text way...
     *
     * @param optional array $trace The array. If null, a current backtrace is used.
     * @param optional boolean $return If true will return the HTML instead of printing it
     *
     * @return void
     */
    public static function printPlainTextDebugBacktrace($trace = null, $return = false)
    {
        if (is_array($trace)) {
            $traceArray = $trace;
        } else {
            $traceArray = debug_backtrace();
        }

        if ($return) {
            ob_start();
        }

        $filenameSize = 5;
        if (is_array($traceArray)) {
            foreach ($traceArray as $trace) {
                if (isset($trace['file'])) {
                    $filenameSize = max($filenameSize, strlen(basename($trace['file'])));
                }
            }
        }
        $filenameSize += 2;

        echo "\n* # ";
        echo "\tFile";
        for ($j = 4; $j < $filenameSize; ++$j) {
            echo ' ';
        }
        echo 'Line';
        echo "\tCall ";
        echo "\n*-----------------------------------------------------------------------------";
        if (is_array($traceArray)) {
            foreach ($traceArray as $i => $trace) {
                /* each $traceArray element represents a step in the call hiearchy. Print them from bottom up. */
                $file = isset($trace['file']) ? basename($trace['file']) : '';
                $line = $trace['line'] ?? '';
                $function = $trace['function'] ?? '';
                $class = $trace['class'] ?? '';
                $type = $trace['type'] ?? '';
                if (isset($trace['args'])) {
                    $args = harmoni_ArgumentRenderer::renderManyArguments($trace['args'], false, false);
                } else {
                    $args = '';
                }

                echo "\n* $i";
                echo "\t".$file;
                for ($j = strlen($file); $j < $filenameSize; ++$j) {
                    echo ' ';
                }
                echo ''.$line;
                echo "\t";
                if ($class || $type || $function || $args) {
                    echo $class.$type.$function.'('.$args.');';
                }
            }
        }

        if ($return) {
            return ob_get_clean();
        }
    }
}
