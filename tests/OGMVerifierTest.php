<?php


namespace Studiow\OGM\Test;

use InvalidArgumentException;
use Studiow\OGM\OGM;
use PHPUnit\Framework\TestCase;


class OGMVerifierTest extends TestCase
{
    public function testItCanValidateCodes()
    {
        $this->assertTrue(OGM::verify('090/9337/55493'));
        $this->assertFalse(OGM::verify('090/9337/55492'));

        //if the control would be zero, it must be replaced with 97
        $this->assertTrue(OGM::verify('090/9337/55897'));
        $this->assertFalse(OGM::verify('090/9337/55800'));
    }

    public function testItCanBeCreatedFromExistingCode()
    {
        $code = '090/9337/55493';

        $ogm = new OGM($code);
        $this->assertEquals('0909337554', $ogm->getBase());
        $this->assertEquals('93', $ogm->getControl());
        $this->assertEquals('090/9337/55493', $ogm->output());
    }

    public function testItRaisesExceptionForMalformedInput()
    {
        $this->expectException(InvalidArgumentException::class);
        new OGM('wrong-input');
    }
}