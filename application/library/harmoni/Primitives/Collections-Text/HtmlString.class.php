<?php

require_once __DIR__.'/HarmoniString.class.php';
define('XML_HTMLSAX3', __DIR__.'/SafeHTML/classes/');
require_once __DIR__.'/SafeHTML/classes/safehtml.php';

/**
 * A HtmlString data type. This class allows for HTML-safe string shortening.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HtmlString.class.php,v 1.19 2008/02/14 20:20:23 adamfranco Exp $
 */
class HtmlString extends HarmoniString
{
    private $_children;
    protected $_string;
    private $safeProtocols;

    public function __construct($string = '')
    {
        $this->_string = (string) $string;
        $this->safeProtocols = [];
    }

    /**
     * Instantiates a new String object with the passed value.
     *
     * @param string $value
     *
     * @return ref object
     *
     * @static
     */
    public static function withValue($value)
    {
        return new self($value);
    }

    /**
     * Instantiates a new String object with the passed value.
     *
     * allowing 'fromString' for string values
     *
     * @param string $aString
     *
     * @return ref object
     *
     * @static
     */
    public static function fromString($aString)
    {
        return new self($aString);
    }

    /**
     * Return a new string with cleaned of XSS-unsafe markup.
     *
     * @param string $html
     *
     * @return string
     *
     * @since 12/6/07
     *
     * @static
     */
    public static function getSafeHtml($html)
    {
        $s = self::withValue($html);
        $s->cleanXSS();

        return $s->asString();
    }

    /**
     * Shorten the string to a number of words, preserving HTML tags
     * while enforcing the closing of html tags.
     *
     * @param int  $numWords
     * @param bool $addElipses
     *
     * @return void
     *
     * @since 12/12/05
     */
    public function trim($numWords, $addElipses = true)
    {
        $tags = [];
        $wordCount = 0;
        $output = '';
        $inWord = false;

        $length = strlen($this->_string);
        for ($i = 0; $i < $length && $wordCount < $numWords; ++$i) {
            $char = $this->_string[$i];

            switch ($char) {
                case '>':
                    $inWord = true;
                    $output .= '&gt;';
                    break;
                case '<':
                    // Just skip past CDATA sections.
                    if (preg_match('/^<!\[CDATA\[$/', substr($this->_string, $i, 9))) {
                        while ($i < $length
                            && !(']' == $this->_string[$i]
                                && ']' == $this->_string[$i + 1]
                                && '>' == $this->_string[$i + 2])
                            && !('}' == $this->_string[$i]
                                && '}' == $this->_string[$i + 1]
                                && '>' == $this->_string[$i + 2])) {
                            $output .= $this->_string[$i];
                            ++$i;
                        }
                        $output .= ']]>';
                        ++$i;
                        ++$i;
                    }

                    // Check for invalid less-than characters
                    elseif ($this->isInvalidLessThan($this->_string, $i)) {
                        $inWord = true;
                        $output .= '&lt;';
                        break;
                    } else {
                        // We are at a tag:
                        //	- 	if we are starting a tag, push that tag onto the tag
                        // 		stack and print it out.
                        //	- 	If we are closing a tag, pop it off of the tag stack.
                        //		and print it out.
                        $tag = $this->getTag($this->_string, $i);
                        $tagHtml = '';
                        $isCloseTag = ('/' == $this->_string[$i + 1]) ? true : false;
                        $isSingleTag = $this->isSingleTag($this->_string, $i);

                        // 					print "<hr>Tag: $tag<br/>isCloseTag: ".(($isCloseTag)?'true':'false')."<br/>isSingleTag: ".(($isSingleTag)?'true':'false');

                        // iterate over the tag
                        while ('>' != $char) {
                            $char = $this->_string[$i];

                            if ('&' == $char) {
                                $rest = substr($this->_string, $i, 25);
                                if (preg_match('/^&((#[0-9]{2,3})|([a-zA-Z][a-zA-Z0-9]{1,20}));/', $rest, $matches)) {
                                    $tagHtml .= $char;
                                } else {
                                    $tagHtml .= '&amp;';
                                }
                            } else {
                                $tagHtml .= $char;
                            }

                            ++$i;
                        }
                        --$i; // we've overrun to print the end tag, so decrement $i

                        // Enforce trailing slashes in single tags for more valid
                        // HTML.
                        if ('comment' == $tag) {
                            $output .= $tagHtml;
                        } else {
                            if ($isSingleTag && '/' != $tagHtml[strlen($tagHtml) - 2]) {
                                $tagHtml[strlen($tagHtml) - 1] = '/';
                                $tagHtml .= '>';
                            }

                            if ($isCloseTag) {
                                if (count($tags)) {
                                    $topTag = array_pop($tags);
                                    $output .= '</'.$topTag.'>';
                                }
                            } elseif ($isSingleTag) {
                                $output .= $tagHtml;
                            } else {
                                $output .= $this->ensureNesting($tag, $tags);
                                $tags[] = $tag;
                                $output .= $tagHtml;
                            }
                        }
                    }

                    break;
                case ' ':
                case "\n":
                case "\r":
                case "\t":
                    if ($inWord) {
                        ++$wordCount;
                        $inWord = false;
                    }
                    $output .= $char;
                    break;
                case '&':
                    $rest = substr($this->_string, $i, 25);
                    if (!preg_match('/^&((#[0-9]{2,3})|([a-zA-Z][a-zA-Z0-9]{1,20}));/', $rest)) {
                        $inWord = true;
                        $output .= '&amp;';
                        break;
                    }
                    // no break
                default:
                    $inWord = true;
                    $output .= $char;
            }
        }

        // trim off any trailing whitespace
        $output = trim($output);

        // If we have text that we aren't printing, print elipses
        // properly nested in HTML
        if ($i < strlen($this->_string) && $addElipses) {
            $addElipses = true;

            $tagsToSkip = 0;
            $nestingTags = ['table', 'tr', 'ul', 'ol', 'select', '![CDATA['];
            for ($i = count($tags); $i > 0; --$i) {
                if (in_array($tags[$i - 1], $nestingTags)) {
                    ++$tagsToSkip;
                } else {
                    break;
                }
            }
        } else {
            $addElipses = false;
            $tagsToSkip = null;
        }

        // if we've hit our word limit and not closed all tags, close them now.
        if (count($tags)) {
            while ($tag = array_pop($tags)) {
                // Ensure that our elipses appear in the proper place in the HTML
                if ($addElipses && 0 === $tagsToSkip) {
                    $output .= dgettext('harmoni', '...');
                }
                --$tagsToSkip;

                $output .= '</'.$tag.'>';
            }

            if ($addElipses && 0 === $tagsToSkip) {
                $output .= dgettext('harmoni', '...');
            }
        } else {
            if ($addElipses) {
                $output .= dgettext('harmoni', '...');
            }
        }

        // 		print "<pre>'".htmlspecialchars($output)."'</pre>";

        $this->_string = $output;
    }

    /**
     * Ensure that td tags are inside of tr's, etc.
     *
     * @param string $tag
     * @param ref array $tags
     *
     * @return string
     *
     * @since 1/27/06
     */
    public function ensureNesting($tag, $tags)
    {
        if (count($tags)) {
            $lastTag = $tags[count($tags) - 1];
        } else {
            $lastTag = null;
        }

        // 		print "<pre>Tag: $tag\nLastTag: $lastTag\nTags => "; print_r($tags); print "</pre>";
        $preString = '';
        switch ($tag) {
            case 'th':
            case 'td':
                if ('tr' != $lastTag) {
                    $preString = $this->ensureNesting('tr', $tags).'<tr>';
                    $tags[] = 'tr';
                }
                break;
            case 'tr':
                if (!in_array($lastTag, ['table', 'tbody', 'thead', 'tfoot'])) {
                    $preString = '<table>';
                    $tags[] = 'table';
                }
                break;
            case 'thead':
            case 'tbody':
            case 'tfoot':
                if ('table' != $lastTag) {
                    $preString = '<table>';
                    $tags[] = 'table';
                }
                break;
            case 'li':
                if ('ul' != $lastTag && 'ol' != $lastTag) {
                    $preString = '<ul>';
                    $tags[] = 'ul';
                }
                break;
            case 'dt':
            case 'dd':
                if ('dl' != $lastTag) {
                    $preString = '<dl>';
                    $tags[] = 'dl';
                }
                break;
            case 'option':
                if ('select' != $lastTag && 'optgroup' != $lastTag) {
                    $preString = '<select>';
                    $tags[] = 'select';
                }
                break;
            case 'optgroup':
                if ('select' != $lastTag) {
                    $preString = '<select>';
                    $tags[] = 'select';
                }
                break;
        }

        return $preString;
    }

    /**
     * Trim the passed text to a shorter length, stripping the HTML tags.
     *
     * Originally posted to php.net forums
     * by webmaster at joshstmarie dot com (55-Sep-2005 05:58).
     * Modified by Adam Franco (afranco at middlebury dot edu).
     *
     * @return string
     *
     * @since 11/21/05
     */
    public function stripTagsAndTrim($word_count)
    {
        $string = strip_tags($this->_string);

        $trimmed = '';
        $string = preg_replace("/\040+/", ' ', trim($string));
        $stringc = explode(' ', $string);

        if ($word_count >= count($stringc)) {
            // nothing to do, our string is smaller than the limit.
            return $string;
        } elseif ($word_count < count($stringc)) {
            // trim the string to the word count
            for ($i = 0; $i < $word_count; ++$i) {
                $trimmed .= $stringc[$i].' ';
            }

            if ('.' == substr($trimmed, strlen(trim($trimmed)) - 1, 1)) {
                return trim($trimmed).'..';
            } else {
                return trim($trimmed).'...';
            }
        }
    }

    /**
     * Clean up the html as much as possible.
     *
     * @return void
     *
     * @since 12/14/05
     */
    public function clean()
    {
        $this->trim(strlen($this->_string));
    }

    /**
     * Clean out any markup that may provide foothold for a cross-site scripting (XSS) attack. * This includes Javascript, frames, etc. This method calls the other stripping methods
     * such as stripJS() and stripFrames(), etc and the only method needed for stripping
     * all XSS-related markup.
     *
     * @return void
     *
     * @since 12/6/07
     */
    public function cleanXSS()
    {
        $this->clean();
        $safeHtml = new SafeHTML();

        // Add on any special protocols
        foreach ($this->safeProtocols as $protocol) {
            $safeHtml->whiteProtocols[] = $protocol;
        }

        $this->_string = $safeHtml->parse($this->_string);
    }

    /**
     * Add a new protocol (i.e. 'feed' for urls like 'feed://www.example.com/')
     * to those allowed to exist in urls. The following protocols are allowed by
     * default:
     *		'ed2k',   'file', 'ftp',  'gopher', 'http',  'https',
     *		'irc',    'mailto', 'news', 'nntp', 'telnet', 'webcal',
     * 		'xmpp',   'callto', 'feed'.
     *
     * @param string $protocol name
     *
     * @return void
     *
     * @since 2/14/08
     */
    public function addSafeProtocol(string $protocol)
    {
        ArgumentValidator::validate($protocal, NonzeroLengthStringValidatorRule::getRule());

        $this->safeProtocols[] = $protocol;
    }

    /**
     * Strip out any Javascript to help prevent XSS attacks.
     *
     * @return void
     *
     * @since 12/6/07
     */
    public function stripJS()
    {
        // <##>
    }

    /**
     * Answer the tag that starts at the given index.
     *
     * @param string $inputString
     * @param int    $tagStart    // index of the opening '<'
     *
     * @return string
     *
     * @since 12/13/05
     */
    private function getTag($inputString, $tagStart)
    {
        if ('/' == $inputString[$tagStart + 1]) {
            $string = substr($inputString, $tagStart + 2);
        } else {
            $string = substr($inputString, $tagStart + 1);
        }

        // Case for comments.
        if (preg_match('/^!--/', $string)) {
            return 'comment';
        }

        $nextSpace = strpos($string, ' ');
        $nextClose = strpos($string, '>');

        if ($nextSpace && $nextSpace < $nextClose) {
            $tagEnd = $nextSpace;
        } else {
            $tagEnd = $nextClose;
        }

        $tag = substr($string, 0, $tagEnd);

        // 		print "<hr>NextSpace: $nextSpace<br/>NextClose: $nextClose<pre>".htmlspecialchars($string)."</pre>";
        // 		print "<pre>".htmlspecialchars($tag)."</pre>";

        return $tag;
    }

    /**
     * Answer true if the tag begining at $tagStart does not have a close-tag,
     * examples are <br/>, <hr/>, <img src=''/>.
     *
     * @param string $inputString
     * @param int    $tagStart    // index of the opening '<'
     *
     * @return string
     *
     * @since 12/13/05
     */
    private function isSingleTag($inputString, $tagStart)
    {
        // if this is a close tag itself, return false
        if ('/' == $inputString[$tagStart + 1]) {
            return false;
        }

        if ('!' == $inputString[$tagStart + 1]
            && '--' == $inputString[$tagStart + 2]
            && '--' == $inputString[$tagStart + 3]) {
            return true;
        }

        // if this is a tag that ends in '/>', return true
        $string = substr($inputString, $tagStart + 1);
        $nextClose = strpos($string, '>');
        if (isset($string[$nextClose - 1]) && '/' == $string[$nextClose - 1]) {
            return true;
        }

        // check the tag to allow exceptions for commonly invalid tags such as
        // <br>, <hr>, <img src=''>
        $tag = $this->getTag($inputString, $tagStart);
        $singleTags = ['br', 'hr', 'img'];
        if (in_array($tag, $singleTags)) {
            return true;
        }

        // Otherwise
        return false;
    }

    /**
     * Answer true if the '<' doesn't seem to be the start of a tag and is
     * instead an invalid 'less-than' character.
     *
     * This will be the case if:
     * 		- There is a space, line-return, new-line, or '=' following the '<'
     *		- Another '<' is found in the string before a '>'
     *
     * @param string $inputString
     * @param int    $tagStart    // index of the opening '<'
     *
     * @return string
     *
     * @since 12/14/05
     */
    private function isInvalidLessThan($inputString, $tagStart)
    {
        // if this '<' is followed by one of our invalid following chars
        $invalidFollowingChars = ["\s", "\t", "\n", "\r", '='];
        if (in_array($inputString[$tagStart + 1], $invalidFollowingChars)) {
            return true;
        }

        // grap the substring starting at our tag.
        for ($i = $tagStart + 1; $i < strlen($inputString); ++$i) {
            if ('<' == $inputString[$i]) {
                return true;
            }
            if ('>' == $inputString[$i]) {
                return false;
            }
        }

        // If we have gotten to the end of the string and not found a
        // closing '>', then the tag must be invalid.
        return true;
    }
}
