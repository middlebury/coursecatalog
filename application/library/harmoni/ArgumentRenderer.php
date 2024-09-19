<?php

// require_once(HARMONI."utilities/ArgumentRenderer.interface.php");

/**
 * An ArgumentRenderer provides functionallity to print/render/format a list of arguments.
 * An ArgumentRenderer provides functionallity to print/render/format a list of arguments.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ArgumentRenderer.class.php,v 1.7 2007/10/09 21:11:59 adamfranco Exp $
 */
class harmoni_ArgumentRenderer
{
    /**
     * Renders one argument.
     * Renders one argument by printing its type and value. In 'detailed' mode,
     * includes some additional information (i.e., for arrays, prints all elements
     * of the array; for objects, prints the object structure).
     *
     * @param mixed $argument    the argument to render
     * @param bool  $isDetailed  if TRUE, will print additional details
     * @param bool  $shouldPrint if TRUE, will print on screen; If FALSE, will not
     *                           print, but just return the result as a string
     * @param int   $trim        If >0, will trim the argument to to the given length
     *
     * @return string The output of the method. This will be output to the browser
     *                if $shouldPrint is set to TRUE.
     **/
    public static function renderOneArgument($argument, $isDetailed = false, $shouldPrint = false, $trim = 0)
    {
        $result = 'Unknown';

        // NULL type
        if (null === $argument) {
            $result = 'NULL';
        }
        // Boolean type
        elseif (is_bool($argument)) {
            $result = 'Boolean: '.($argument ? 'true' : 'false');
        }
        // String type
        elseif (is_string($argument)) {
            if ($trim > 0 && strlen($argument) > $trim) {
                $result = 'String: "'.substr($argument, 0, $trim).'"...(trimmed)';
            } else {
                $result = "String: \"$argument\"";
            }
            if ($isDetailed) {
                $result .= ' (length = '.strlen($argument).')';
            }
        }
        // Integer type
        elseif (is_int($argument)) {
            $result = "Integer: $argument";
        }
        // Float type
        elseif (is_float($argument)) {
            $result = "Float: $argument";
        }
        // Array type
        elseif (is_array($argument)) {
            $result = 'Array: '.count($argument).' elements';
            if ($isDetailed && count($argument) > 0) {
                $result .= " {\n";
                foreach ($argument as $key => $elt) {
                    $result .= '    ['.$key.'] => ';
                    $result .= self::renderOneArgument($elt, false, false, $trim);
                    $result .= "\n";
                }
                $result .= '}';
            }
        }
        // Resource type
        elseif (is_resource($argument)) {
            $result = 'Resource: '.get_resource_type($argument);
        }
        // Object type
        elseif (is_object($argument)) {
            $result = 'Object: '.$argument::class;
            if ($isDetailed) {
                $memberVars = get_object_vars($argument);
                if (count($memberVars) > 0) {
                    $result .= "\nMember variables: {\n";
                    foreach (get_object_vars($argument) as $key => $elt) {
                        $result .= '    ['.$key.'] => ';
                        $result .= self::renderOneArgument($elt, false, false, $trim);
                        $result .= "\n";
                    }
                    $result .= '}';
                }
            }
        }

        // print if necessary
        if ($shouldPrint) {
            echo $result;
        }

        return $result;
    }

    /**
     * Renders many arguments.
     * Renders many arguments by printing their types and values and comma delimiting them.
     * In 'detailed' mode, includes some additional information (i.e., for arrays,
     * prints all elements of the array; for objects, prints the object structure).
     *
     * @param array $arguments   the arguments to render
     * @param bool  $isDetailed  if TRUE, will print additional details
     * @param bool  $shouldPrint If TRUE, will print on screen; If FALSE, will not
     * @param int   $trim        if >0, will trim the argument to to the given length
     *                           print, but just return the result as a string
     *
     * @return string The output of the method. This will be output to the browser
     *                if $shouldPrint is set to TRUE. Returns FALSE, if something goes wrong.
     **/
    public static function renderManyArguments($arguments, $isDetailed = false, $shouldPrint = false, $trim = 0)
    {
        // see if $arguments is an array
        if (!is_array($arguments)) {
            return false;
        }
        // make sure $arguments is not empty
        if (0 == count($arguments)) {
            return false;
        }

        // render each element of $arguments
        $resultArray = [];
        foreach (array_keys($arguments) as $i => $key) {
            $resultArray[] = self::renderOneArgument($arguments[$key], $isDetailed, false, $trim);
        }

        $glue = ($isDetailed) ? ",\n" : ', ';
        $result = implode($glue, $resultArray);

        // print if necessary
        if ($shouldPrint) {
            echo $result;
        }

        return $result;
    }
}
