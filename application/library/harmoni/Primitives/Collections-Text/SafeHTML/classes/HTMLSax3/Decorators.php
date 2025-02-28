<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Alexander Zhukov <alex@veresk.ru> Original port from Python |
// | Authors: Harry Fuecks <hfuecks@phppatterns.com> Port to PEAR + more  |
// | Authors: Many @ Sitepointforums Advanced PHP Forums                  |
// +----------------------------------------------------------------------+
//
// $Id: Decorators.php,v 1.2 2007/12/06 20:54:48 adamfranco Exp $
//
/**
 * Decorators for dealing with parser options.
 *
 * @version $Id: Decorators.php,v 1.2 2007/12/06 20:54:48 adamfranco Exp $
 *
 * @see XML_HTMLSax3::set_option
 */
/**
 * Trims the contents of element data from whitespace at start and end.
 *
 * Changes by Adam Franco
 * - 2007-12-06 - Removed return and pass-by reference ampersands to match PHP5 strict-mode.
 */
class XML_HTMLSax3_Trim
{
    /**
     * Original handler object.
     *
     * @var object
     */
    public $orig_obj;
    /**
     * Original handler method.
     *
     * @var string
     */
    public $orig_method;

    /**
     * Constructs XML_HTMLSax3_Trim.
     *
     * @param handler object being decorated
     * @param string original handler method
     */
    public function __construct($orig_obj, $orig_method)
    {
        $this->orig_obj = $orig_obj;
        $this->orig_method = $orig_method;
    }

    /**
     * Trims the data.
     *
     * @param XML_HTMLSax3
     * @param string element data
     */
    public function trimData($parser, $data)
    {
        $data = trim($data);
        if ('' != $data) {
            $this->orig_obj->{$this->orig_method}($parser, $data);
        }
    }
}
/**
 * Coverts tag names to upper case.
 */
class XML_HTMLSax3_CaseFolding
{
    /**
     * Original handler object.
     *
     * @var object
     */
    public $orig_obj;
    /**
     * Original open handler method.
     *
     * @var string
     */
    public $orig_open_method;
    /**
     * Original close handler method.
     *
     * @var string
     */
    public $orig_close_method;

    /**
     * Constructs XML_HTMLSax3_CaseFolding.
     *
     * @param handler object being decorated
     * @param string original open handler method
     * @param string original close handler method
     */
    public function __construct($orig_obj, $orig_open_method, $orig_close_method)
    {
        $this->orig_obj = $orig_obj;
        $this->orig_open_method = $orig_open_method;
        $this->orig_close_method = $orig_close_method;
    }

    /**
     * Folds up open tag callbacks.
     *
     * @param XML_HTMLSax3
     * @param string tag name
     * @param array tag attributes
     */
    public function foldOpen($parser, $tag, $attrs = [], $empty = false)
    {
        $this->orig_obj->{$this->orig_open_method}($parser, strtoupper($tag), $attrs, $empty);
    }

    /**
     * Folds up close tag callbacks.
     *
     * @param XML_HTMLSax3
     * @param string tag name
     */
    public function foldClose($parser, $tag, $empty = false)
    {
        $this->orig_obj->{$this->orig_close_method}($parser, strtoupper($tag), $empty);
    }
}
/**
 * Breaks up data by linefeed characters, resulting in additional
 * calls to the data handler.
 */
class XML_HTMLSax3_Linefeed
{
    /**
     * Original handler object.
     *
     * @var object
     */
    public $orig_obj;
    /**
     * Original handler method.
     *
     * @var string
     */
    public $orig_method;

    /**
     * Constructs XML_HTMLSax3_LineFeed.
     *
     * @param handler object being decorated
     * @param string original handler method
     */
    public function __construct($orig_obj, $orig_method)
    {
        $this->orig_obj = $orig_obj;
        $this->orig_method = $orig_method;
    }

    /**
     * Breaks the data up by linefeeds.
     *
     * @param XML_HTMLSax3
     * @param string element data
     */
    public function breakData($parser, $data)
    {
        $data = explode("\n", $data);
        foreach ($data as $chunk) {
            $this->orig_obj->{$this->orig_method}($parser, $chunk);
        }
    }
}
/**
 * Breaks up data by tab characters, resulting in additional
 * calls to the data handler.
 */
class XML_HTMLSax3_Tab
{
    /**
     * Original handler object.
     *
     * @var object
     */
    public $orig_obj;
    /**
     * Original handler method.
     *
     * @var string
     */
    public $orig_method;

    /**
     * Constructs XML_HTMLSax3_Tab.
     *
     * @param handler object being decorated
     * @param string original handler method
     */
    public function __construct($orig_obj, $orig_method)
    {
        $this->orig_obj = $orig_obj;
        $this->orig_method = $orig_method;
    }

    /**
     * Breaks the data up by linefeeds.
     *
     * @param XML_HTMLSax3
     * @param string element data
     */
    public function breakData($parser, $data)
    {
        $data = explode("\t", $data);
        foreach ($data as $chunk) {
            $this->orig_obj->{$this->orig_method}($this, $chunk);
        }
    }
}
/**
 * Breaks up data by XML entities and parses them with html_entity_decode(),
 * resulting in additional calls to the data handler<br />
 * Requires PHP 4.3.0+.
 */
class XML_HTMLSax3_Entities_Parsed
{
    /**
     * Original handler object.
     *
     * @var object
     */
    public $orig_obj;
    /**
     * Original handler method.
     *
     * @var string
     */
    public $orig_method;

    /**
     * Constructs XML_HTMLSax3_Entities_Parsed.
     *
     * @param handler object being decorated
     * @param string original handler method
     */
    public function __construct($orig_obj, $orig_method)
    {
        $this->orig_obj = $orig_obj;
        $this->orig_method = $orig_method;
    }

    /**
     * Breaks the data up by XML entities.
     *
     * @param XML_HTMLSax3
     * @param string element data
     */
    public function breakData($parser, $data)
    {
        $data = preg_split('/(&.+?;)/', $data, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY);
        foreach ($data as $chunk) {
            $chunk = html_entity_decode($chunk, \ENT_NOQUOTES);
            $this->orig_obj->{$this->orig_method}($this, $chunk);
        }
    }
}
/*
 * Compatibility with older PHP versions
 */
if (version_compare(\PHP_VERSION, '4.3', '<') && !function_exists('html_entity_decode')) {
    function html_entity_decode($str, $style = \ENT_NOQUOTES)
    {
        return strtr($str,
            array_flip(get_html_translation_table(\HTML_ENTITIES, $style)));
    }
}
/**
 * Breaks up data by XML entities but leaves them unparsed,
 * resulting in additional calls to the data handler<br />.
 */
class XML_HTMLSax3_Entities_Unparsed
{
    /**
     * Original handler object.
     *
     * @var object
     */
    public $orig_obj;
    /**
     * Original handler method.
     *
     * @var string
     */
    public $orig_method;

    /**
     * Constructs XML_HTMLSax3_Entities_Unparsed.
     *
     * @param handler object being decorated
     * @param string original handler method
     */
    public function __construct($orig_obj, $orig_method)
    {
        $this->orig_obj = $orig_obj;
        $this->orig_method = $orig_method;
    }

    /**
     * Breaks the data up by XML entities.
     *
     * @param XML_HTMLSax3
     * @param string element data
     */
    public function breakData($parser, $data)
    {
        $data = preg_split('/(&.+?;)/', $data, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY);
        foreach ($data as $chunk) {
            $this->orig_obj->{$this->orig_method}($this, $chunk);
        }
    }
}

/**
 * Strips the HTML comment markers or CDATA sections from an escape.
 * If XML_OPTIONS_FULL_ESCAPES is on, this decorator is not used.<br />.
 */
class XML_HTMLSax3_Escape_Stripper
{
    /**
     * Original handler object.
     *
     * @var object
     */
    public $orig_obj;
    /**
     * Original handler method.
     *
     * @var string
     */
    public $orig_method;

    /**
     * Constructs XML_HTMLSax3_Entities_Unparsed.
     *
     * @param handler object being decorated
     * @param string original handler method
     */
    public function __construct($orig_obj, $orig_method)
    {
        $this->orig_obj = $orig_obj;
        $this->orig_method = $orig_method;
    }

    /**
     * Breaks the data up by XML entities.
     *
     * @param XML_HTMLSax3
     * @param string element data
     */
    public function strip($parser, $data)
    {
        // Check for HTML comments first
        if ('--' == substr($data, 0, 2)) {
            $patterns = [
                '/^\-\-/',          // Opening comment: --
                '/\-\-$/',          // Closing comment: --
            ];
            $data = preg_replace($patterns, '', $data);

        // Check for XML CDATA sections (note: don't do both!)
        } elseif ('[' == substr($data, 0, 1)) {
            $patterns = [
                '/^\[.*CDATA.*\[/s', // Opening CDATA
                '/\].*\]$/s',       // Closing CDATA
            ];
            $data = preg_replace($patterns, '', $data);
        }

        $this->orig_obj->{$this->orig_method}($this, $data);
    }
}
