<?php

/**
 * @since 5/5/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObject.class.php,v 1.5 2007/10/10 22:58:34 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

/**
 * SObject (Squeak/Smalltalk-like object).
 *
 * In Smalltalk, all object share a common class, "Object", which defines common
 * methods for all objects. This class holds a subset of those methods in Object
 * that are needed in this package.
 *
 * @since 5/5/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SObject.class.php,v 1.5 2007/10/10 22:58:34 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
abstract class SObject
{
    /*********************************************************
     * Class Methods
     *********************************************************/

    /**
     * Create an object that has similar contents to aSimilarObject.
     * If the classes have any instance varaibles with the same names, copy them across.
     * If this is bad for a class, override this method.
     *
     * @param string $targetClass    As mentiond here,
     *                               {@link http://www.php.net/manual/en/ref.classobj.php} there is no good way
     *                               to inherit class methods such that they can know the class of
     *                               the reciever (child class) instead of the class name of the implementer
     *                               (parent class). As such, we need to pass our target classname.
     * @param object $aSimilarObject
     *
     * @return object
     *
     * @static
     *
     * @since 5/5/05
     */
    public static function newFrom($targetClass, $aSimilarObject)
    {
        $newObject = new $targetClass();
        $newObject->copySameFrom($aSimilarObject);

        return $newObject;
    }

    /*********************************************************
     * Instance Methods - Comparing
     *********************************************************/

    /**
     * Answer whether the receiver and the argument are the same.
     * If = is redefined in any subclass, consider also redefining the
     * message hash.
     *
     * @param object $anObject
     *
     * @return bool
     *
     * @since 7/11/05
     */
    public function isEqualTo($anObject)
    {
        return $this == $anObject;
    }

    /**
     * Answer whether the receiver and the argument are the same.
     *
     * WARNING: This method is here for convience. DO NOT OVERRIDE.
     * OVERRIDE isEqualTo() instead.
     *
     * @param object $anObject
     *
     * @return bool
     *
     * @since 7/11/05
     */
    public function isEqual($anObject)
    {
        return $this->isEqualTo($anObject);
    }

    /**
     * Answer whether the receiver and the argument are not the
     * same.
     *
     * @param object $anObject
     *
     * @return bool
     *
     * @since 7/11/05
     */
    public function isNotEqualTo($anObject)
    {
        return !$this->isEqualTo($anObject);
    }

    /**
     * Answer whether the receiver and the argument Reference the same object.
     *
     * @param object $anObject
     *
     * @return bool
     *
     * @since 7/11/05
     */
    public function isReferenceTo($anObject)
    {
        return $this === $anObject;
    }

    /**
     * Answer whether the receiver and the argument do not reference the same object.
     *
     * @param object $anObject
     *
     * @return bool
     *
     * @since 7/11/05
     */
    public function isNotReferenceTo($anObject)
    {
        return !$this->isReferenceTo($anObject);
    }

    /*********************************************************
     * Instance Methods - Converting
     *********************************************************/

    /**
     * Create an object of class aSimilarClass that has similar contents to the
     * receiver.
     *
     * 'as' seems to be a reserved word, so 'asA' is used instead.
     *
     * @param string $aSimilarClass
     *
     * @return object
     *
     * @since 5/5/05
     */
    public function asA($aSimilarClass)
    {
        $obj = self::newFrom($aSimilarClass, $this);

        return $obj;
    }

    /**
     * Answer a String whose characters are a description of the receiver.
     * To change behavior, override printableString(), not this method.
     *
     * @return string
     *
     * @since 7/11/05
     */
    public function asString()
    {
        return $this->printableString();
    }

    /**
     * Answer a String whose characters are a description of the receiver.
     * Override this method as needed to provide a better representation.
     *
     * @return string
     *
     * @since 7/11/05
     */
    public function printableString()
    {
        $classname = static::class;
        $string = 'a';

        if (in_array(strtolower($classname[0]), ['a', 'e', 'i', 'o', 'u'])) {
            $string .= 'n';
        }

        $string .= ' '.$classname;

        return $string;
    }

    /*********************************************************
     * Instance Methods - Copying
     *********************************************************/

    /**
     * Answer another instance just like the receiver. Subclasses typically
     * override postCopy; they typically do not override shallowCopy.
     *
     * @return object
     *
     * @since 7/11/05
     */
    public function copy()
    {
        $newObject = $this->shallowCopy();

        return $newObject->postCopy();
    }

    /**
     * Copy to myself all instance variables named the same in otherObject.
     * This ignores otherObject's control over its own inst vars.
     *
     * @param object $otherObject
     *
     * @return void
     *
     * @since 5/5/05
     */
    public function copySameFrom($otherObject)
    {
        $myVars = get_object_vars($this);
        $otherVars = get_object_vars($otherObject);

        foreach (array_keys($myVars) as $varName) {
            if (array_key_exists($varName, $otherVars)) {
                $this->$varName = $otherVars[$varName];
            }
        }
    }

    /**
     * one more level than a shallowCopy.
     *
     * @return object
     *
     * @since 7/11/05
     */
    public function copyTwoLevel()
    {
        $class = static::class;
        $newObject = new $class();

        $varList = array_keys(get_object_vars($this));
        foreach ($varList as $varName) {
            // Use shallow-copy if we can
            if (is_object($this->$varName)
                && method_exists($this->$varName, 'shallowCopy')) {
                $newObject->$varName = $this->$varName->shallowCopy();
            }

            // Otherwise use PHP's copy-by-value
            else {
                $newObject->$varName = $this->$varName;
            }
        }

        return $newObject;
    }

    /**
     * Answer a copy of the receiver with its own copy of each instance
     * variable.
     *
     * @return object
     *
     * @since 7/11/05
     */
    public function deepCopy()
    {
        $class = static::class;
        $newObject = new $class();

        $varList = array_keys(get_object_vars($this));
        foreach ($varList as $varName) {
            // Use deep-copy if we can
            if (is_object($this->$varName)
                && method_exists($this->$varName, 'deepCopy')) {
                $newObject->$varName = $this->$varName->deepCopy();
            }

            // If it is an Array, copy the values
            elseif (is_array($this->$varName)) {
                $newObject->$varName = self::_deepCopyArray($this->$varName);
            }

            // Otherwise use PHP's copy-by-value
            else {
                $newObject->$varName = $this->$varName;
            }
        }

        return $newObject;
    }

    /**
     * Recursively copy an array, used by deepCopy.
     *
     * @param array, the input array
     *
     * @return array, a deep copy of the input array
     *
     * @since 7/12/05
     *
     * @static
     */
    public static function _deepCopyArray($array)
    {
        $newArray = [];

        foreach (array_keys($array) as $key) {
            // Use deep-copy if we can on Objects
            if (is_object($array[$key])
                && method_exists($array[$key], 'deepCopy')) {
                $newArray[$key] = $array[$key]->deepCopy();
            }

            // If it is an Array, copy the values
            elseif (is_array($array[$key])) {
                $newArray[$key] = self::_deepCopyArray($array[$key]);
            }

            // Otherwise use PHP's copy-by-value
            else {
                $newArray[$key] = $array[$key];
            }
        }

        return $newArray;
    }

    /**
     * $this is a shallow copy, subclasses should override to copy fields as
     * necessary to complete the full copy.
     *
     * @return object
     *
     * @since 7/11/05
     */
    public function postCopy()
    {
        /* override to copy fields as necessary to complete the full copy. */
        return $this;
    }

    /**
     * Answer a copy of the receiver which shares the receiver's instance variables.
     *
     * @return object
     *
     * @since 7/11/05
     */
    public function shallowCopy()
    {
        $class = static::class;
        $newObject = new $class();

        $varList = array_keys(get_object_vars($this));
        foreach ($varList as $varName) {
            if (is_object($this->$varName)) {
                $newObject->$varName = $this->$varName;
            } else {
                $newObject->$varName = $this->$varName;
            }
        }

        return $newObject;
    }
}
