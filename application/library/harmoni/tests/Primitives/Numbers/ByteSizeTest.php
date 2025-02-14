<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ByteSizeTestCase.class.php,v 1.4 2007/09/04 20:25:28 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 *
 * @since 5/25/05
 */

use PHPUnit\Framework\TestCase;

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/25/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ByteSizeTestCase.class.php,v 1.4 2007/09/04 20:25:28 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class ByteSizeTest extends TestCase
{
    private ByteSize $bNum;
    private ByteSize $kbNum;
    private ByteSize $mbNum;
    private ByteSize $gbNum;
    private ByteSize $reallyBigNum;

    /**
     *  Sets up unit test wide variables at the start
     *	 of each test method.
     */
    protected function setUp(): void
    {
        $this->bNum = ByteSize::withValue(280);
        $this->kbNum = ByteSize::withValue(2800);
        $this->mbNum = ByteSize::withValue(2808093);
        $this->gbNum = ByteSize::withValue(8808033932);
        $this->reallyBigNum = ByteSize::withValue(80000000000000000000000000);
    }

    /**
     *	  Clears the data set in the setUp() method call.
     */
    protected function tearDown(): void
    {
        // perhaps, unset $obj here
    }

    public function testPowerOf2()
    {
        // 		print "\n<br/>bNum<br/>";
        $this->assertEquals(280, round($this->bNum->multipleOfPowerOf2(0), 2));
        $this->assertEquals(0.27, round($this->bNum->multipleOfPowerOf2(10), 2));
        $this->assertEquals(0.00, round($this->bNum->multipleOfPowerOf2(20), 2));
        $this->assertEquals(0.00, round($this->bNum->multipleOfPowerOf2(30), 2));
        $this->assertEquals(0.00, round($this->bNum->multipleOfPowerOf2(40), 2));
        $this->assertEquals(0.00, round($this->bNum->multipleOfPowerOf2(50), 2));
        $this->assertEquals(0.00, round($this->bNum->multipleOfPowerOf2(60), 2));
        $this->assertEquals(0.00, round($this->bNum->multipleOfPowerOf2(70), 2));
        $this->assertEquals(0.00, round($this->bNum->multipleOfPowerOf2(80), 2));

        // 		print "\n<br/>kbNum<br/>";
        $this->assertEquals(2800, round($this->kbNum->multipleOfPowerOf2(0), 2));
        $this->assertEquals(2.73, round($this->kbNum->multipleOfPowerOf2(10), 2));
        $this->assertEquals(0.00, round($this->kbNum->multipleOfPowerOf2(20), 2));
        $this->assertEquals(0.00, round($this->kbNum->multipleOfPowerOf2(30), 2));
        $this->assertEquals(0.00, round($this->kbNum->multipleOfPowerOf2(40), 2));
        $this->assertEquals(0.00, round($this->kbNum->multipleOfPowerOf2(50), 2));
        $this->assertEquals(0.00, round($this->kbNum->multipleOfPowerOf2(60), 2));
        $this->assertEquals(0.00, round($this->kbNum->multipleOfPowerOf2(70), 2));
        $this->assertEquals(0.00, round($this->kbNum->multipleOfPowerOf2(80), 2));

        // 		print "\n<br/>mbNum<br/>";
        $this->assertEquals(2808093, round($this->mbNum->multipleOfPowerOf2(0), 2));
        $this->assertEquals(2742.28, round($this->mbNum->multipleOfPowerOf2(10), 2));
        $this->assertEquals(2.68, round($this->mbNum->multipleOfPowerOf2(20), 2));
        $this->assertEquals(0.00, round($this->mbNum->multipleOfPowerOf2(30), 2));
        $this->assertEquals(0.00, round($this->mbNum->multipleOfPowerOf2(40), 2));
        $this->assertEquals(0.00, round($this->mbNum->multipleOfPowerOf2(50), 2));
        $this->assertEquals(0.00, round($this->mbNum->multipleOfPowerOf2(60), 2));
        $this->assertEquals(0.00, round($this->mbNum->multipleOfPowerOf2(70), 2));
        $this->assertEquals(0.00, round($this->mbNum->multipleOfPowerOf2(80), 2));

        // 		print "\n<br/>gbNum<br/>";
        $this->assertEquals(8808033932, round($this->gbNum->multipleOfPowerOf2(0), 2));
        $this->assertEquals(8601595.64, round($this->gbNum->multipleOfPowerOf2(10), 2));
        $this->assertEquals(8400.00, round($this->gbNum->multipleOfPowerOf2(20), 2));
        $this->assertEquals(8.20, round($this->gbNum->multipleOfPowerOf2(30), 2));
        $this->assertEquals(0.01, round($this->gbNum->multipleOfPowerOf2(40), 2));
        $this->assertEquals(0.00, round($this->gbNum->multipleOfPowerOf2(50), 2));
        $this->assertEquals(0.00, round($this->gbNum->multipleOfPowerOf2(60), 2));
        $this->assertEquals(0.00, round($this->gbNum->multipleOfPowerOf2(70), 2));
        $this->assertEquals(0.00, round($this->gbNum->multipleOfPowerOf2(80), 2));

        // 		print "\n<br/>reallybignum<br/>";
        $this->assertEquals(80000000000000000000000000, round($this->reallyBigNum->multipleOfPowerOf2(0), 2));
        $this->assertEquals(72759576141834.27, round($this->reallyBigNum->multipleOfPowerOf2(40), 2));
        $this->assertEquals(71054273576.01, round($this->reallyBigNum->multipleOfPowerOf2(50), 2));
        $this->assertEquals(69388939.04, round($this->reallyBigNum->multipleOfPowerOf2(60), 2));
        $this->assertEquals(67762.64, round($this->reallyBigNum->multipleOfPowerOf2(70), 2));
        $this->assertEquals(66.17, round($this->reallyBigNum->multipleOfPowerOf2(80), 2));
    }

    public function testPrintableString()
    {
        $this->assertEquals('280 B', $this->bNum->printableString());
        $this->assertEquals('2.73 kB', $this->kbNum->printableString());
        $this->assertEquals('2.68 MB', $this->mbNum->printableString());
        $this->assertEquals('8.20 GB', $this->gbNum->printableString());
        $this->assertEquals('66.17 YB', $this->reallyBigNum->printableString());
    }

    public function testFromString()
    {
        $num = ByteSize::fromString('280 B');
        $this->assertEquals('280 B', $num->printableString());
        $this->assertEquals(280, round($num->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('2800 B');
        $this->assertEquals('2.73 kB', $num->printableString());
        $this->assertEquals(2800, round($num->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('2.73 kB');
        $this->assertEquals('2.73 kB', $num->printableString());
        $this->assertEquals(2796, round($num->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('2.73kB');
        $this->assertEquals('2.73 kB', $num->printableString());
        $this->assertEquals(2796, round($num->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('2.73kb');
        $this->assertEquals('2.73 kB', $num->printableString());
        $this->assertEquals(2796, round($num->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('2.73 KB');
        $this->assertEquals('2.73 kB', $num->printableString());
        $this->assertEquals(2796, round($num->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('2.73KB');
        $this->assertEquals('2.73 kB', $num->printableString());
        $this->assertEquals(2796, round($num->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('2.73		kB');
        $this->assertEquals('2.73 kB', $num->printableString());
        $this->assertEquals(2796, round($num->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('2.68 MB');
        $this->assertEquals('2.68 MB', $num->printableString());

        $num = ByteSize::fromString('8.20 GB');
        $this->assertEquals('8.20 GB', $num->printableString());

        $num = ByteSize::fromString('66.17 YB');
        $this->assertEquals('66.17 YB', $num->printableString());
        $this->assertEquals(80000000000000000000000000, round($this->reallyBigNum->multipleOfPowerOf2(0), 2));

        $num = ByteSize::fromString('80000000000000000000000000 B');
        $this->assertEquals('66.17 YB', $num->printableString());
        $this->assertEquals(80000000000000000000000000, round($this->reallyBigNum->multipleOfPowerOf2(0), 2));
    }
}
