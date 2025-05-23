<?php

use PHPUnit\Framework\TestCase;

/**
 * Test class for banner_course_Course.
 * Generated by PHPUnit on 2009-04-15 at 13:36:26.
 */
class banner_course_CourseDescriptionConversionTest extends TestCase
{
    public function testSampleDescription()
    {
        // First Line.
        $this->assertEquals(
            'This is some text. Shakespeare wrote <em>The Merchant of Venice</em> as well as <em>Macbeth</em>. Words can have slashes in them such as AC/DC, but this does not indicate italics.',
            banner_course_Course::convertDescription(
                'This is some text. Shakespeare wrote /The Merchant of Venice/ as well as /Macbeth/. Words can have slashes in them such as AC/DC, but this does not indicate italics.'
            )
        );
        // Second Line.
        $this->assertEquals(
            'Spaces around slashes such as this / don\'t cause italics either. Quotes may be <em>"used inside slashes",</em> or "<em>outside of them</em>". <strong>Bold Text</strong> should have asterisk characters around it. Like slashes, * can be used surrounded by spaces, or surrounded by letters or numbers and not cause bold formatting: 4*5 = 20 or 4 * 5 = 20. Numbers as well as text can be bold <strong>42</strong> or italic <em>85</em>',
            banner_course_Course::convertDescription(
                'Spaces around slashes such as this / don\'t cause italics either. Quotes may be /"used inside slashes",/ or "/outside of them/". *Bold Text* should have asterisk characters around it. Like slashes, * can be used surrounded by spaces, or surrounded by letters or numbers and not cause bold formatting: 4*5 = 20 or 4 * 5 = 20. Numbers as well as text can be bold *42* or italic /85/'
            )
        );

        $this->assertEquals(
            'This is some text. Shakespeare wrote <em>The Merchant of Venice</em> as well as <em>Macbeth</em>. Words can have slashes in them such as AC/DC, but this does not indicate italics.<br />
<br />
Spaces around slashes such as this / don\'t cause italics either. Quotes may be <em>"used inside slashes",</em> or "<em>outside of them</em>". <strong>Bold Text</strong> should have asterisk characters around it. Like slashes, * can be used surrounded by spaces, or surrounded by letters or numbers and not cause bold formatting: 4*5 = 20 or 4 * 5 = 20. Numbers as well as text can be bold <strong>42</strong> or italic <em>85</em>',
            banner_course_Course::convertDescription(
                'This is some text. Shakespeare wrote /The Merchant of Venice/ as well as /Macbeth/. Words can have slashes in them such as AC/DC, but this does not indicate italics.

Spaces around slashes such as this / don\'t cause italics either. Quotes may be /"used inside slashes",/ or "/outside of them/". *Bold Text* should have asterisk characters around it. Like slashes, * can be used surrounded by spaces, or surrounded by letters or numbers and not cause bold formatting: 4*5 = 20 or 4 * 5 = 20. Numbers as well as text can be bold *42* or italic /85/'
            )
        );
    }

    public function testHtmlStartOfDescription()
    {
        // Bold.
        $this->assertEquals(
            '<strong>Hello World.</strong> Foo.',
            banner_course_Course::convertDescription(
                '<strong>Hello World.</strong> Foo.'
            )
        );
        $this->assertEquals(
            '<strong>Hello World.</strong> Foo.',
            banner_course_Course::convertDescription(
                '<b>Hello World.</b> Foo.'
            )
        );
        $this->assertEquals(
            '<strong>Hello World.</strong> Foo.',
            banner_course_Course::convertDescription(
                '<strong>Hello World.</strong> Foo.'
            )
        );
        // Italic.
        $this->assertEquals(
            '<strong>Hello World.</strong> Foo.',
            banner_course_Course::convertDescription(
                '<strong>Hello World.</strong> Foo.'
            )
        );
        $this->assertEquals(
            '<strong>Hello World.</strong> Foo.',
            banner_course_Course::convertDescription(
                '<b>Hello World.</b> Foo.'
            )
        );
        $this->assertEquals(
            '<strong>Hello World.</strong> Foo.',
            banner_course_Course::convertDescription(
                '<strong>Hello World.</strong> Foo.'
            )
        );
        // Paragraph -- these should be stripped.
        $this->assertEquals(
            'Hello World. Foo.',
            banner_course_Course::convertDescription(
                '<p>Hello World.</p> Foo.'
            )
        );
    }

    public function testMoreHtml()
    {
        $this->assertEquals(
            '<strong>Artificial Intelligence and International Security</strong><br />
Welcome to a course that delves into the heart of 21st-century power dynamics. We\'ll explore how the digital revolution, spearheaded by AI and tech giants, is reshaping international security and social norms. From presidents being deplatformed to elections influenced by foreign startups, we\'ll analyze real-world cases that highlight the shifting balance between governments and the private sector. The course encourages critical thinking about the ethical implications of this new world order: How do we support equity and protect democratic values in an era of unprecedented technological power? Through lively debates and hands-on projects, students will develop the skills to navigate and influence the complex intersection of technology, politics, and social justice in a rapidly evolving international system. Special attention will be given to career opportunities in this critical issue area.',
            banner_course_Course::convertDescription(
                '<b>Artificial Intelligence and International Security</b>
Welcome to a course that delves into the heart of 21st-century power dynamics. We\'ll explore how the digital revolution, spearheaded by AI and tech giants, is reshaping international security and social norms. From presidents being deplatformed to elections influenced by foreign startups, we\'ll analyze real-world cases that highlight the shifting balance between governments and the private sector. The course encourages critical thinking about the ethical implications of this new world order: How do we support equity and protect democratic values in an era of unprecedented technological power? Through lively debates and hands-on projects, students will develop the skills to navigate and influence the complex intersection of technology, politics, and social justice in a rapidly evolving international system. Special attention will be given to career opportunities in this critical issue area.'
            )
        );
    }

    public function testItalicsAtStart()
    {
        $this->assertEquals(
            '<em>Italic text</em> is great.',
            banner_course_Course::convertDescription(
                '/Italic text/ is great.'
            )
        );
    }

    public function testItalicsAtEnd()
    {
        $this->assertEquals(
            'This course examines conservation and environmental policy in the United States. In order to better understand the current nature of the conservation and environmental policy process, we will begin by tracing the development of past ideas, institutions, and policies related to this policy arena. We will then focus on contemporary conservation and environmental politics and policy making—gridlock in Congress, interest group pressure, the role of the courts and the president, and a move away from national policy making—toward the states, collaboration, and civil society. 3 hrs. lect./disc. <em>(American Politics)</em>',
            banner_course_Course::convertDescription(
                'This course examines conservation and environmental policy in the United States. In order to better understand the current nature of the conservation and environmental policy process, we will begin by tracing the development of past ideas, institutions, and policies related to this policy arena. We will then focus on contemporary conservation and environmental politics and policy making—gridlock in Congress, interest group pressure, the role of the courts and the president, and a move away from national policy making—toward the states, collaboration, and civil society. 3 hrs. lect./disc. /(American Politics)/'
            )
        );
    }

    public function testBoldAtStart()
    {
        $this->assertEquals(
            '<strong>Bold text</strong> is great.',
            banner_course_Course::convertDescription(
                '*Bold text* is great.'
            )
        );
    }

    public function testBoldAtEnd()
    {
        $this->assertEquals(
            'This is some <strong>bold text</strong>',
            banner_course_Course::convertDescription(
                'This is some *bold text*'
            )
        );
    }
}
