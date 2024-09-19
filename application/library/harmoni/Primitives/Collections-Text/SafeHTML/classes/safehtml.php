<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * SafeHTML Parser.
 *
 * PHP versions 4 and 5
 *
 * Changes by Adam Franco
 * - 2007-12-06 - Removed return and pass-by reference ampersands to match PHP5 strict-mode.
 *
 * @category   HTML
 *
 * @author     Roman Ivanov <thingol@mail.ru>
 * @copyright  2004-2005 Roman Ivanov
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 *
 * @version    1.3.7
 *
 * @see       http://pixel-apes.com/safehtml/
 */

/**
 * This package requires HTMLSax3 package.
 */
require_once XML_HTMLSAX3.'HTMLSax3.php';

/**
 * SafeHTML Parser.
 *
 * This parser strips down all potentially dangerous content within HTML:
 * <ul>
 * <li>opening tag without its closing tag</li>
 * <li>closing tag without its opening tag</li>
 * <li>any of these tags: "base", "basefont", "head", "html", "body", "applet",
 * "object", "iframe", "frame", "frameset", "script", "layer", "ilayer", "embed",
 * "bgsound", "link", "meta", "style", "title", "blink", "xml" etc.</li>
 * <li>any of these attributes: on*, data*, dynsrc</li>
 * <li>javascript:/vbscript:/about: etc. protocols</li>
 * <li>expression/behavior etc. in styles</li>
 * <li>any other active content</li>
 * </ul>
 * It also tries to convert code to XHTML valid, but htmltidy is far better
 * solution for this task.
 *
 * <b>Example:</b>
 * <pre>
 * $parser = new SafeHTML();
 * $result = $parser->parse($doc);
 * </pre>
 *
 * @category   HTML
 *
 * @author     Roman Ivanov <thingol@mail.ru>
 * @copyright  1997-2005 Roman Ivanov
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 *
 * @version    Release: @package_version@
 *
 * @see       http://pear.php.net/package/SafeHTML
 */
class safehtml
{
    /**
     * Storage for resulting HTML output.
     *
     * @var string
     */
    public $_xhtml = '';

    /**
     * Array of counters for each tag.
     *
     * @var array
     */
    public $_counter = [];

    /**
     * Stack of unclosed tags.
     *
     * @var array
     */
    public $_stack = [];

    /**
     * Array of counters for tags that must be deleted with all content.
     *
     * @var array
     */
    public $_dcCounter = [];

    /**
     * Stack of unclosed tags that must be deleted with all content.
     *
     * @var array
     */
    public $_dcStack = [];

    /**
     * Stores level of list (ol/ul) nesting.
     *
     * @var int
     */
    public $_listScope = 0;

    /**
     * Stack of unclosed list tags.
     *
     * @var array
     */
    public $_liStack = [];

    /**
     * Array of prepared regular expressions for protocols (schemas) matching.
     *
     * @var array
     */
    public $_protoRegexps = [];

    /**
     * Array of prepared regular expressions for CSS matching.
     *
     * @var array
     */
    public $_cssRegexps = [];

    /**
     * List of single tags ("<tag />").
     *
     * @var array
     */
    public $singleTags = ['area', 'br', 'img', 'input', 'hr', 'wbr'];

    /**
     * List of dangerous tags (such tags will be deleted).
     *
     * @var array
     */
    public $deleteTags = [
        'applet', 'base',   'basefont', 'bgsound', 'blink',  'body',
        'embed',  'frame',  'frameset', 'head',    'html',   'ilayer',
        'iframe', 'layer',  'link',     'meta',    'object', 'style',
        'title',  'script',
    ];

    /**
     * List of dangerous tags (such tags will be deleted, and all content
     * inside this tags will be also removed).
     *
     * @var array
     */
    public $deleteTagsContent = ['script', 'style', 'title', 'xml'];

    /**
     * Type of protocols filtering ('white' or 'black').
     *
     * @var string
     */
    public $protocolFiltering = 'white';

    /**
     * List of "dangerous" protocols (used for blacklist-filtering).
     *
     * @var array
     */
    public $blackProtocols = [
        'about',   'chrome',     'data',       'disk',     'hcp',
        'help',    'javascript', 'livescript', 'lynxcgi',  'lynxexec',
        'ms-help', 'ms-its',     'mhtml',      'mocha',    'opera',
        'res',     'resource',   'shell',      'vbscript', 'view-source',
        'vnd.ms.radio',          'wysiwyg',
    ];

    /**
     * List of "safe" protocols (used for whitelist-filtering).
     *
     * @var array
     */
    public $whiteProtocols = [
        'ed2k',   'file', 'ftp',  'gopher', 'http',  'https',
        'irc',    'mailto', 'news', 'nntp', 'telnet', 'webcal',
        'xmpp',   'callto', 'feed',
    ];

    /**
     * List of attributes that can contain protocols.
     *
     * @var array
     */
    public $protocolAttributes = [
        'action', 'background', 'codebase', 'dynsrc', 'href', 'lowsrc', 'src',
    ];

    /**
     * List of dangerous CSS keywords.
     *
     * Whole style="" attribute will be removed, if parser will find one of
     * these keywords
     *
     * @var array
     */
    public $cssKeywords = [
        'absolute', 'behavior',       'behaviour',   'content', 'expression',
        'fixed',    'include-source', 'moz-binding',
    ];

    /**
     * List of tags that can have no "closing tag".
     *
     * @var array
     *
     * @deprecated XHTML does not allow such tags
     */
    public $noClose = [];

    /**
     * List of block-level tags that terminates paragraph.
     *
     * Paragraph will be closed when this tags opened
     *
     * @var array
     */
    public $closeParagraph = [
        'address', 'blockquote', 'center', 'dd',      'dir',       'div',
        'dl',      'dt',         'h1',     'h2',      'h3',        'h4',
        'h5',      'h6',         'hr',     'isindex', 'listing',   'marquee',
        'menu',    'multicol',   'ol',     'p',       'plaintext', 'pre',
        'table',   'ul',         'xmp',
    ];

    /**
     * List of table tags, all table tags outside a table will be removed.
     *
     * @var array
     */
    public $tableTags = [
        'caption', 'col', 'colgroup', 'tbody', 'td', 'tfoot', 'th',
        'thead',   'tr',
    ];

    /**
     * List of list tags.
     *
     * @var array
     */
    public $listTags = ['dir', 'menu', 'ol', 'ul', 'dl'];

    /**
     * List of dangerous attributes.
     *
     * @var array
     */
    public $attributes = ['dynsrc', 'id', 'name'];

    /**
     * List of allowed "namespaced" attributes.
     *
     * @var array
     */
    public $attributesNS = ['xml:lang'];

    /**
     * Constructs class.
     */
    public function __construct()
    {
        // making regular expressions based on Proto & CSS arrays
        foreach ($this->blackProtocols as $proto) {
            $preg = "/[\s\x01-\x1F]*";
            for ($i = 0; $i < strlen($proto); ++$i) {
                $preg .= $proto[$i]."[\s\x01-\x1F]*";
            }
            $preg .= ':/i';
            $this->_protoRegexps[] = $preg;
        }

        foreach ($this->cssKeywords as $css) {
            $this->_cssRegexps[] = '/'.$css.'/i';
        }

        return true;
    }

    /**
     * Handles the writing of attributes - called from $this->_openHandler().
     *
     * @param array $attrs array of attributes $name => $value
     *
     * @return bool
     */
    public function _writeAttrs($attrs)
    {
        if (is_array($attrs)) {
            foreach ($attrs as $name => $value) {
                $name = strtolower($name);

                if (str_starts_with($name, 'on')) {
                    continue;
                }
                if (str_starts_with($name, 'data')) {
                    continue;
                }
                if (in_array($name, $this->attributes)) {
                    continue;
                }
                if (!preg_match('/^[a-z0-9]+$/i', $name)) {
                    if (!in_array($name, $this->attributesNS)) {
                        continue;
                    }
                }

                if ((true === $value) || null === $value) {
                    $value = $name;
                }

                if ('style' == $name) {
                    // removes insignificant backslahes
                    $value = str_replace('\\', '', $value);

                    // removes CSS comments
                    while (1) {
                        $_value = preg_replace("!/\*.*?\*/!s", '', $value);
                        if ($_value == $value) {
                            break;
                        }
                        $value = $_value;
                    }

                    // replace all & to &amp;
                    $value = str_replace('&amp;', '&', $value);
                    $value = str_replace('&', '&amp;', $value);

                    foreach ($this->_cssRegexps as $css) {
                        if (preg_match($css, $value)) {
                            continue 2;
                        }
                    }
                    foreach ($this->_protoRegexps as $proto) {
                        if (preg_match($proto, $value)) {
                            continue 2;
                        }
                    }
                }

                $tempval = preg_replace_callback('/&#(\d+);?/m', function ($m) { return chr($m[1]); }, $value); // "'
                $tempval = preg_replace_callback('/&#x([0-9a-f]+);?/mi', function ($m) { return chr(hexdec($m[1])); }, $tempval);

                if (in_array($name, $this->protocolAttributes)
                    && str_contains($tempval, ':')) {
                    if ('black' == $this->protocolFiltering) {
                        foreach ($this->_protoRegexps as $proto) {
                            if (preg_match($proto, $tempval)) {
                                continue 2;
                            }
                        }
                    } else {
                        $_tempval = explode(':', $tempval);
                        $proto = $_tempval[0];
                        if (!in_array($proto, $this->whiteProtocols)) {
                            continue;
                        }
                    }
                }

                $value = str_replace('"', '&quot;', $value);
                $this->_xhtml .= ' '.$name.'="'.$value.'"';
            }
        }

        return true;
    }

    /**
     * Opening tag handler - called from HTMLSax.
     *
     * @param object $parser HTML Parser
     * @param string $name   tag name
     * @param array  $attrs  tag attributes
     *
     * @return bool
     */
    public function _openHandler($parser, $name, $attrs)
    {
        $name = strtolower($name);

        if (in_array($name, $this->deleteTagsContent)) {
            $this->_dcStack[] = $name;
            $this->_dcCounter[$name] = isset($this->_dcCounter[$name]) ? $this->_dcCounter[$name] + 1 : 1;
        }
        if (0 != count($this->_dcStack)) {
            return true;
        }

        if (in_array($name, $this->deleteTags)) {
            return true;
        }

        if (!preg_match('/^[a-z0-9]+$/i', $name)) {
            if (preg_match("!(?:\@|://)!i", $name)) {
                $this->_xhtml .= '&lt;'.$name.'&gt;';
            }

            return true;
        }

        if (in_array($name, $this->singleTags)) {
            $this->_xhtml .= '<'.$name;
            $this->_writeAttrs($attrs);
            $this->_xhtml .= ' />';

            return true;
        }

        // TABLES: cannot open table elements when we are not inside table
        if ((isset($this->_counter['table'])) && ($this->_counter['table'] <= 0)
            && in_array($name, $this->tableTags)) {
            return true;
        }

        // PARAGRAPHS: close paragraph when closeParagraph tags opening
        if (in_array($name, $this->closeParagraph) && in_array('p', $this->_stack)) {
            $this->_closeHandler($parser, 'p');
        }

        // LISTS: we should close <li> if <li> of the same level opening
        if ('li' == $name && count($this->_liStack)
            && $this->_listScope == $this->_liStack[count($this->_liStack) - 1]) {
            $this->_closeHandler($parser, 'li');
        }

        // LISTS: we want to know on what nesting level of lists we are
        if (in_array($name, $this->listTags)) {
            ++$this->_listScope;
        }
        if ('li' == $name) {
            $this->_liStack[] = $this->_listScope;
        }

        $this->_xhtml .= '<'.$name;
        $this->_writeAttrs($attrs);
        $this->_xhtml .= '>';
        $this->_stack[] = $name;
        $this->_counter[$name] = isset($this->_counter[$name]) ? $this->_counter[$name] + 1 : 1;

        return true;
    }

    /**
     * Closing tag handler - called from HTMLSax.
     *
     * @param string $name tag name
     *
     * @return bool
     */
    public function _closeHandler($parser, $name)
    {
        $name = strtolower($name);

        if (isset($this->_dcCounter[$name]) && ($this->_dcCounter[$name] > 0)
            && in_array($name, $this->deleteTagsContent)) {
            while ($name != ($tag = array_pop($this->_dcStack))) {
                --$this->_dcCounter[$tag];
            }

            --$this->_dcCounter[$name];
        }

        if (0 != count($this->_dcStack)) {
            return true;
        }

        if ((isset($this->_counter[$name])) && ($this->_counter[$name] > 0)) {
            while ($name != ($tag = array_pop($this->_stack))) {
                $this->_closeTag($tag);
            }

            $this->_closeTag($name);
        }

        return true;
    }

    /**
     * Closes tag.
     *
     * @param string $tag tag name
     *
     * @return bool
     */
    public function _closeTag($tag)
    {
        if (!in_array($tag, $this->noClose)) {
            $this->_xhtml .= '</'.$tag.'>';
        }

        --$this->_counter[$tag];

        if (in_array($tag, $this->listTags)) {
            --$this->_listScope;
        }

        if ('li' == $tag) {
            array_pop($this->_liStack);
        }

        return true;
    }

    /**
     * Character data handler - called from HTMLSax.
     *
     * @param object $parser HTML parser
     * @param string $data   textual data
     *
     * @return bool
     */
    public function _dataHandler($parser, $data)
    {
        if (0 == count($this->_dcStack)) {
            $this->_xhtml .= $data;
        }

        return true;
    }

    /**
     * Escape handler - called from HTMLSax.
     *
     * @param object $parser HTML parser
     * @param string $data   comments or other type of data
     *
     * @return bool
     */
    public function _escapeHandler($parser, $data)
    {
        return true;
    }

    /**
     * Returns the XHTML document.
     *
     * @return string Processed (X)HTML document
     */
    public function getXHTML()
    {
        while ($tag = array_pop($this->_stack)) {
            $this->_closeTag($tag);
        }

        return $this->_xhtml;
    }

    /**
     * Clears current document data.
     *
     * @return bool
     */
    public function clear()
    {
        $this->_xhtml = '';

        return true;
    }

    /**
     * Main parsing fuction.
     *
     * @param string $doc HTML document for processing
     *
     * @return string Processed (X)HTML document
     */
    public function parse($doc)
    {
        // Save all '<' symbols
        $doc = preg_replace("/<(?=[^a-zA-Z\/\!\?\%])/", '&lt;', $doc);

        // Web documents shouldn't contains \x00 symbol
        $doc = str_replace("\x00", '', $doc);

        // Opera6 bug workaround
        $doc = str_replace("\xC0\xBC", '&lt;', $doc);

        // UTF-7 encoding ASCII decode
        $doc = $this->repackUTF7($doc);

        // Instantiate the parser
        $parser = new XML_HTMLSax3();

        // Set up the parser
        $parser->set_object($this);

        $parser->set_element_handler('_openHandler', '_closeHandler');
        $parser->set_data_handler('_dataHandler');
        $parser->set_escape_handler('_escapeHandler');

        $parser->parse($doc);

        return $this->getXHTML();
    }

    /**
     * UTF-7 decoding fuction.
     *
     * @param string $str HTML document for recode ASCII part of UTF-7 back to ASCII
     *
     * @return string Decoded document
     */
    public function repackUTF7($str)
    {
        return preg_replace_callback('!\+([0-9a-zA-Z/]+)\-!', [$this, 'repackUTF7Callback'], $str);
    }

    /**
     * Additional UTF-7 decoding fuction.
     *
     * @param string $str String for recode ASCII part of UTF-7 back to ASCII
     *
     * @return string Recoded string
     */
    public function repackUTF7Callback($str)
    {
        $str = base64_decode($str[1]);
        $str = preg_replace_callback('/^((?:\x00.)*)((?:[^\x00].)+)/', [$this, 'repackUTF7Back'], $str);

        return preg_replace('/\x00(.)/', '$1', $str);
    }

    /**
     * Additional UTF-7 encoding fuction.
     *
     * @param string $str String for recode ASCII part of UTF-7 back to ASCII
     *
     * @return string Recoded string
     */
    public function repackUTF7Back($str)
    {
        return $str[1].'+'.rtrim(base64_encode($str[2]), '=').'-';
    }
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
