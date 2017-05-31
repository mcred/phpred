<?php
namespace PHPRed;

/**
* @covers \PHPRed\PHPRed
*/
class PHPRedTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
    }

    public function testCanInstantiate()
    {
        $phpred = new PHPRed();
        $this->assertInstanceOf(PHPRed::class, $phpred);
    }
}
