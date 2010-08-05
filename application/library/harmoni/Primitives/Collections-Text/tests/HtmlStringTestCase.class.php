<?php
/** 
 * @package harmoni.primitives.collections-text.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HtmlStringTestCase.class.php,v 1.11 2007/09/04 20:25:27 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../HtmlString.class.php");

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/3/05
 *
 * @package harmoni.primitives.collections-text.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HtmlStringTestCase.class.php,v 1.11 2007/09/04 20:25:27 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class HtmlStringTestCase extends UnitTestCase {
	
	/**
	*  Sets up unit test wide variables at the start
	*	 of each test method.
	*	 @access public
	*/
	function setUp() {
		
	}
	
	/**
	 *	  Clears the data set in the setUp() method call.
	 *	  @access public
	 */
	function tearDown() {
		// perhaps, unset $obj here
	}
	
	/**
	 * Test the creation methods.
	 */ 
	function test_trim_tag_handling() {
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(100);
		$this->assertEqual($htmlString->asString(), $string);
		
		// test single html tags <hr/>
		$string = 
"Hello world.<hr/>
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(100);
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"Hello world.<img src='' border='1' />
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(100);
		$this->assertEqual($htmlString->asString(), $string);
		
		// Test bad html tags.
		$string = 
"Hello world.<hr> <img src='' border='1'>
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.<hr/> <img src='' border='1'/>
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(100);
		$this->assertEqual($htmlString->asString(), $result);
		
		// test re-nesting
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong><em>fox</strong></em> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong><em>fox</em></strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString->trim(100);
		$this->assertEqual($htmlString->asString(), $string);
	}
	
	
	/**
	 * Test unescaped less-thans and greater-thans.
	 */
	function test_unescaped_less_greater () {
		$string = 
"Hello < world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello &lt; world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(100);
		$this->assertEqual($htmlString->asString(), $result);
		
		$string = 
"Hello > world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello &gt; world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(100);
		$this->assertEqual($htmlString->asString(), $result);
		
		$string = 
"Hello > world.";
		$result = 
"Hello &gt; world.";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(3);
		$this->assertEqual($htmlString->asString(), $result);
		
		$string = 
"Hello <world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello &lt;world.
<p style='font-size: large;'>The...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(3);
		$this->assertEqual($htmlString->asString(), $result);
		
		$string = 
"Hello<world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello&lt;world.
<p style='font-size: large;'>The quick...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(3);
		$this->assertEqual($htmlString->asString(), $result);
	}
	
	/**
	 * Test the creation methods.
	 */ 
	function test_trim_lengths() {
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello...";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(1);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world....";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(2);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello   \n \n\t \n\r  world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello   \n \n\t \n\r  world....";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(2);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(3);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(4);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(5);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong>...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(6);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(7);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(8);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(9);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy...</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(10);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(11);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(12);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(13);
		$this->assertEqual($htmlString->asString(), $result);
	}
	
	/**
	 * Test Elipses
	 */
	function test_elipses () {
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world....";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(2);
		$this->assertEqual($htmlString->asString(), $result);
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 
jumped over the lazy <em>dog</em>.</p>";
		$result = 
"Hello world.";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(2, false);
		$this->assertEqual($htmlString->asString(), $result);
	}
	
	/**
	 * Test unescaped less-thans and greater-thans.
	 */
	function test_trimming_in_nested () {
		$string = 
"Hello world.
<div>
	<ul>
		<li>This is one</li>
		<li>This is two</li>
		<li>This is three</li>
		<li>This is four</li>
		<li>This is five</li>
	</ul>
</div>";
		$result = 
"Hello world.
<div>
	<ul>
		<li>This is one</li>
		<li>This is two</li></ul>...</div>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(8);
		$this->assertEqual($htmlString->asString(), $result);
		
		$string = 
"Hello world.
<div>
	<ul>
		<li>This is one</li>
		<li>This is two</li>
		<li>This is three</li>
		<li>This is four</li>
		<li>This is five</li>
	</ul>
</div>";
		$result = 
"Hello world.
<div>
	<ul>
		<li>This is one</li>
		<li>This is...</li></ul></div>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(7);
		$this->assertEqual($htmlString->asString(), $result);
		
		
		
		$string = 
"Hello world.
<div>
	<table>
		<tr>
			<td>This is one</td>
			<td>This is two</td>
		</tr>
		<tr>
			<td>This is three</td>
			<td>This is four</td>
		</tr>
	</table>
</div>";
		$result = 
"Hello world.
<div>
	<table>
		<tr>
			<td>This is...</td></tr></table></div>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(4);
		$this->assertEqual($htmlString->asString(), $result);
		
		$string = 
"Hello world.
<div>
	<table>
		<tr>
			<td>This is one</td>
			<td>This is two</td>
		</tr>
		<tr>
			<td>This is three</td>
			<td>This is four</td>
		</tr>
	</table>
</div>";
		$result = 
"Hello world.
<div>
	<table>
		<tr>
			<td>This is one</td></tr></table>...</div>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(5);
		$this->assertEqual($htmlString->asString(), $result);
	}
	
	/**
	 * Test a real-life block of html
	 */
	function test_dc_block () {
		ob_start();
		print<<<END
<h2>Dublin Core Metadata Element Set, Version 1.1: Reference Description</h2>
<a name="introduction" id="introduction"></a> 
<h3>Introduction</h3>
<p>The Dublin Core metadata element set is a standard for cross-domain
information resource description. Here an information resource is
defined to be "anything that has identity". This is the definition used
in Internet RFC 2396, "Uniform Resource Identifiers (URI): Generic
Syntax", by Tim Berners-Lee et al. There are no fundamental
restrictions to the types of resources to which Dublin Core metadata
can be assigned.</p>
<p>Three formally endorsed versions exist of the Dublin Core Metadata Element Set, version 1.1:</p>

<ul>
<li>ISO Standard 15836-2003 (February 2003): <a href="http://www.niso.org/international/SC4/n515.pdf">http://www.niso.org/international/SC4/n515.pdf</a></li>
<li>NISO Standard Z39.85-2001 (September 2001): <a href="http://www.niso.org/standards/resources/Z39-85.pdf">http://www.niso.org/standards/resources/Z39-85.pdf</a></li>
<li>CEN Workshop Agreement CWA 13874 (March 2000, no longer available)</li></ul>
<p>The current document has been brought into line with the ISO and
NISO standards. The more comprehensive document "DCMI Metadata Terms" (<a href="http://dublincore.org/documents/dcmi-terms/">http://dublincore.org/documents/dcmi-terms/</a>) includes the latest and authoritative term declarations for the Dublin Core Metadata Element Set, Version 1.1.</p>
<p>For an overview and links to full specifications of all metadata
terms maintained by the Dublin Core Metadata Initiative please see:: <a href="http://dublincore.org/usage/documents/overview/">http://dublincore.org/usage/documents/overview/</a>.</p>
END;
		$string = ob_get_clean();
		
		ob_start();
		print<<<END
<h2>Dublin Core Metadata Element Set, Version 1.1: Reference Description</h2>
<a name="introduction" id="introduction"></a> 
<h3>Introduction</h3>
<p>The Dublin Core metadata element set is a standard for cross-domain
information resource description. Here an information resource is
defined to be "anything that has identity". This is the definition used
in Internet RFC 2396, "Uniform Resource Identifiers (URI): Generic
Syntax", by Tim Berners-Lee et al. There are no fundamental
restrictions to the types of resources to which Dublin Core metadata
can be assigned.</p>
<p>Three formally endorsed versions exist of the Dublin Core Metadata Element Set, version 1.1:</p>

<ul>
<li>ISO Standard 15836-2003 (February 2003): <a href="http://www.niso.org/international/SC4/n515.pdf">http://www.niso.org/international/SC4/n515.pdf</a></li>
<li>NISO Standard Z39.85-2001 (September 2001):...</li></ul>
END;
		$result = ob_get_clean();
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(99);
		$this->assertEqual($htmlString->asString(), $result);	
		
		
		
		ob_start();
		print<<<END
<h2>Dublin Core Metadata Element Set, Version 1.1: Reference Description</h2>
<a name="introduction" id="introduction"></a> 
<h3>Introduction</h3>
<p>The Dublin Core metadata element set is a standard for cross-domain
information resource description. Here an information resource is
defined to be "anything that has identity". This is the definition used
in Internet RFC 2396, "Uniform Resource Identifiers (URI): Generic
Syntax", by Tim Berners-Lee et al. There are no fundamental
restrictions to the types of resources to which Dublin Core metadata
can be assigned.</p>
<p>Three formally endorsed versions exist of the Dublin Core Metadata Element Set, version 1.1:</p>

<ul>
<li>ISO Standard 15836-2003 (February 2003): <a href="http://www.niso.org/international/SC4/n515.pdf">http://www.niso.org/international/SC4/n515.pdf</a></li>
<li>NISO Standard Z39.85-2001 (September 2001): <a href="http://www.niso.org/standards/resources/Z39-85.pdf">http://www.niso.org/standards/resources/Z39-85.pdf</a></li>
<li>CEN Workshop Agreement CWA 13874 (March 2000, no longer available)</li></ul>
<p>The current document has been brought into line with the ISO and
NISO standards. The more comprehensive document "DCMI Metadata Terms" (<a href="http://dublincore.org/documents/dcmi-terms/">http://dublincore.org/documents/dcmi-terms/</a>) includes the latest and authoritative term declarations for the Dublin Core Metadata Element Set, Version 1.1.</p>
<p>For an overview and links to full specifications of all metadata
terms maintained by the Dublin Core Metadata Initiative please see:: <a href="http://dublincore.org/usage/documents/overview/">http://dublincore.org/usage/documents/overview/</a>.</p>
END;
		$string = ob_get_clean();
		
		ob_start();
		print<<<END
<h2>Dublin Core Metadata Element Set, Version 1.1: Reference Description</h2>
<a name="introduction" id="introduction"></a> 
<h3>Introduction</h3>
<p>The Dublin Core metadata element set is a standard for cross-domain
information resource description. Here an information resource is
defined to be "anything that has identity". This is the definition used
in Internet RFC 2396, "Uniform Resource Identifiers (URI): Generic
Syntax", by Tim Berners-Lee et al. There are no fundamental
restrictions to the types of resources to which Dublin Core metadata
can be assigned.</p>
<p>Three formally endorsed versions exist of the Dublin Core Metadata Element Set, version 1.1:</p>

<ul>
<li>ISO Standard 15836-2003 (February 2003): <a href="http://www.niso.org/international/SC4/n515.pdf">http://www.niso.org/international/SC4/n515.pdf</a></li>
<li>NISO Standard Z39.85-2001 (September 2001): <a href="http://www.niso.org/standards/resources/Z39-85.pdf">http://www.niso.org/standards/resources/Z39-85.pdf</a></li></ul>...
END;
		$result = ob_get_clean();
		$htmlString = HtmlString::withValue($string);
		$htmlString->trim(100);
		$this->assertEqual($htmlString->asString(), $result);
		

	}
	
	/**
	 * Test CDATA sections
	 */ 
	function test_cdata() {
		$string = "<![CDATA[Hello.]]>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"<![CDATA[
Hello.
]]>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"<![CDATA[  <  ]]>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"<![CDATA[  >  ]]>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = "<![CDATA[&]]>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = "<![CDATA[ & ]]>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = "Hello there <![CDATA[ & ]]> with CDATA";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"Blah Blah

<p> Below is CDATA:

<![CDATA[
 && lordy > dloryg
]]>;

<br/>Above is CDATA.
</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"<div>
	<p>Hello there</p>
	<style>
		<![CDATA[ & ]]> 
	</style>
	with CDATA.
</div>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"<![CDATA[
		&
		<
		>
]]>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		
		$string = 
"Hello world.
<p style='font-size: large;'>The quick brown <strong>fox</strong> 

<![CDATA[
	& < >
]]>

jumped over the lazy <em>dog</em>.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"<![CDATA[
Hello.
";
		$string2 = 
"<![CDATA[
Hello.
]]>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
	}
	
	
	function test_comments () {
		$string = 
"Hello 
<!---->
Goodbye";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"Hello 
<!-- -->
Goodbye";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
		
		$string = 
"Hello 
<!-- You my 
mommy.-->
Goodbye";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string);
	}
	
	function test_close_tags () {
		$string = 
"</div><p>Hello world.</p>";
		$string2 = 
"<p>Hello world.</p>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"</td></tr></table><p>Hello </div> world.</p>";
		$string2 = 
"<p>Hello </p> world.";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
	}
	
	function test_tables () {
		$string = 
"<tr><td>Hello world.</td></tr>";
		$string2 = 
"<table><tr><td>Hello world.</td></tr></table>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"<td>Hello world.</td>";
		$string2 = 
"<table><tr><td>Hello world.</td></tr></table>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"<th>Hello world.</th>";
		$string2 = 
"<table><tr><th>Hello world.</th></tr></table>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"<tbody><th>Hello world.</th>";
		$string2 = 
"<table><tbody><tr><th>Hello world.</th></tr></tbody></table>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
	}
	
	function test_lists () {
		$string = 
"<li>Hello world.</li>";
		$string2 = 
"<ul><li>Hello world.</li></ul>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"<li>Hello world.";
		$string2 = 
"<ul><li>Hello world.</li></ul>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"<dt>saying:</dt>";
		$string2 = 
"<dl><dt>saying:</dt></dl>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"<dt>saying:</dt>
<dd>Hello world.</dd>";
		$string2 = 
"<dl><dt>saying:</dt>
<dd>Hello world.</dd></dl>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
	}
	
	function test_select () {
		$string = 
"<option>Hello world.";
		$string2 = 
"<select><option>Hello world.</option></select>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"<optgroup><option>Hello world.</optgroup></option>";
		$string2 = 
"<select><optgroup><option>Hello world.</option></optgroup></select>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
		
		$string = 
"<optgroup><option>Hello world.</optgroup></option></select>";
		$string2 = 
"<select><optgroup><option>Hello world.</option></optgroup></select>";
		$htmlString = HtmlString::withValue($string);
		$htmlString->clean();
		$this->assertEqual($htmlString->asString(), $string2);
	}
}
?>