<?php
namespace PHPRed;

use \Prophecy;

/**
* @covers \PHPRed\PHPRed
*/
class PHPRedTest extends \PHPUnit\Framework\TestCase
{
    private $prophet;
    private $mysqli;

    public function setup()
    {
        $this->prophet = new Prophecy\Prophet;
        $this->mysqli = $this->prophet->prophesize("\mysqli");
    }

    public function testCanInstantiate()
    {
        $phpred = new PHPRed($this->mysqli->reveal());
        $this->assertInstanceOf(PHPRed::class, $phpred);
    }
}
