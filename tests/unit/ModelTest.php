<?php
namespace PHPRed;

use \Prophecy;

/**
* @covers \PHPRed\Model
*/
class ModelTest extends \PHPUnit\Framework\TestCase
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
        $model = new Model($this->mysqli->reveal());
        $this->assertInstanceOf(Model::class, $model);
    }
}
