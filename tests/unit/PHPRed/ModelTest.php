<?php
namespace PHPRed\Models;

use \MysqliDb;
use \Prophecy;

/**
* @covers PHPRed\Models\Model
*/
class ModelTest extends \PHPUnit\Framework\TestCase
{
    private $pipe;
    private $prophet;
    private $mysql;
    private $pipes;
    private $services;
    private $pipesServices;
    private $pipesUsers;
    private $users;

    public function setup()
    {
        $this->pipes = [
            [
                'id' => 1,
                'name' => 'Daily Weather Report',
                'pipe_name' => 'DailyWeatherReport'
            ],
            [
                'id' => 2,
                'name' => 'New Payments To Slack',
                'pipe_name' => 'NewPaymentsToSlack'
            ]
        ];
        $this->services = [
            'id' => 1,
            'display_name' => 'Mailgun',
            'class_name' => 'Mailgun'
        ];
        $this->pipesServices = [
            'id' => 1,
            'pipe_id' => 1,
            'service_id' => 1
        ];
        $this->pipesUsers = [[
            'id' => 1,
            'pipe_id' => 1,
            'user_id' => 1,
            'last_run' => '2017-07-04 10:20:24',
            'run_count' => 3,
            'tracking_condition' => '',
            'enabled' => 1
        ]];
        $this->users = [[
            'id' => 1,
            'email' => 'derek@grindaga.com'
        ]];
        $this->prophet = new Prophecy\Prophet;
        $this->mysql = $this->prophet->prophesize("\MysqliDb");
        $this->pipe = new \Custom\Pipe($this->mysql->reveal());
        $this->pipeUser = new \Custom\PipeUser($this->mysql->reveal());
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(\Custom\Pipe::class, $this->pipe);
    }

    public function testCanGetAll()
    {
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('int'))->willReturn(true);
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->join(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn($this->pipes);
        $this->mysql->get(\Prophecy\Argument::exact('pipes_users PipeUser'))->willReturn($this->pipesUsers);
        $this->mysql->get(\Prophecy\Argument::exact('pipes_services pipes_services'), \Prophecy\Argument::type('null'), \Prophecy\Argument::type('array'))->willReturn($this->pipesUsers);

        $pipes = $this->pipe->getAll();
        $this->assertEquals('DailyWeatherReport', $pipes[0]['pipe_name']);
    }

    public function testCanGetById()
    {
        $pipes = [
            [
                'id' => 2,
                'name' => 'New Payments To Slack',
                'pipe_name' => 'NewPaymentsToSlack'
            ]
        ];
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('int'))->willReturn(true);
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('null'))->willReturn(true);
        $this->mysql->join(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn($pipes);
        $this->mysql->get(\Prophecy\Argument::exact('pipes_users PipeUser'))->willReturn([]);
        $this->mysql->get(\Prophecy\Argument::exact('pipes_services pipes_services'), \Prophecy\Argument::type('null'), \Prophecy\Argument::type('array'))->willReturn([]);

        $pipes = $this->pipe->getById(2);
        $this->assertEquals('NewPaymentsToSlack', $pipes['pipe_name']);
    }

    public function testCanInsert()
    {
        $pipe = [
            'name' => 'New Payments To Slack',
            'pipe_name' => 'NewPaymentsToSlack'
        ];
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn([]);
        $this->mysql->startTransaction()->willReturn(true);
        $this->mysql->insert(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('array'))->willReturn(true);
        $this->mysql->getInsertId()->willReturn(3);
        $this->mysql->commit()->willReturn(true);

        $newPipe = $this->pipe->insert($pipe);
        $this->assertEquals(3, $newPipe['id']);
    }

    public function testCanNotInsert()
    {
        $pipe = [
            'name' => 'New Payments To Slack',
            'pipe_name' => 'NewPaymentsToSlack'
        ];
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn([]);
        $this->mysql->startTransaction()->willReturn(true);
        $this->mysql->insert(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('array'))->willReturn(false);
        $this->mysql->rollback()->willReturn(true);
        $this->mysql->getLastError()->willReturn(true);

        $this->expectException('InvalidArgumentException');
        $newPipe = $this->pipe->insert($pipe);
    }

    public function testCanUpdateById()
    {
        $pipe = [
            'name' => 'New Payments To Slack',
            'pipe_name' => 'NewPaymentsToSlack'
        ];
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('int'))->willReturn(true);
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn([]);
        $this->mysql->startTransaction()->willReturn(true);
        $this->mysql->update(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('array'))->willReturn(true);
        $this->mysql->getInsertId()->willReturn(3);
        $this->mysql->commit()->willReturn(true);

        $newPipe = $this->pipe->updateById(3, $pipe);
        $this->assertEquals('NewPaymentsToSlack', $newPipe['pipe_name']);
    }

    public function testNotCanUpdateById()
    {
        $pipe = [
            'name' => 'New Payments To Slack',
            'pipe_name' => 'NewPaymentsToSlack'
        ];
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('int'))->willReturn(true);
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn([]);
        $this->mysql->startTransaction()->willReturn(true);
        $this->mysql->update(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('array'))->willReturn(false);
        $this->mysql->rollback()->willReturn(true);
        $this->mysql->getLastError()->willReturn(true);

        $this->expectException('InvalidArgumentException');
        $newPipe = $this->pipe->updateById(3, $pipe);
    }

    public function testCanDeleteById()
    {
        $this->mysql->startTransaction()->willReturn(true);
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('int'))->willReturn(true);
        $this->mysql->delete(\Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->commit()->willReturn(true);

        $newPipe = $this->pipe->deleteById(3);
        $this->assertNull($newPipe);
    }

    public function testCanNotDeleteById()
    {
        $this->mysql->startTransaction()->willReturn(true);
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('int'))->willReturn(true);
        $this->mysql->delete(\Prophecy\Argument::type('string'))->willReturn(false);
        $this->mysql->rollback()->willReturn(true);
        $this->mysql->getLastError()->willReturn(true);

        $this->expectException('InvalidArgumentException');
        $this->pipe->deleteById(3);
    }

    public function testMissingRequiredFields()
    {
        $pipe = [
            'name' => 'New Payments To Slack',
        ];
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn([]);
        $this->mysql->startTransaction()->willReturn(true);


        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('pipe_name is required for Pipe.');
        $this->pipe->insert($pipe);
    }

    public function testFieldsAreNotUnique()
    {
        $pipe = [
            'name' => 'New Payments To Slack',
            'pipe_name' => 'NewPaymentsToSlack'
        ];
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn([$pipe]);
        $this->mysql->startTransaction()->willReturn(true);
        $this->mysql->insert(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('array'))->willReturn(true);
        $this->mysql->getInsertId()->willReturn(3);
        $this->mysql->commit()->willReturn(true);

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('name must be unique for Pipe.');
        $this->pipe->insert($pipe);
    }

    public function testBelongsTo()
    {
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('int'))->willReturn(true);
        $this->mysql->where(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->join(\Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'), \Prophecy\Argument::type('string'))->willReturn(true);
        $this->mysql->get(\Prophecy\Argument::exact('pipes Pipe'))->willReturn($this->pipes);
        $this->mysql->get(\Prophecy\Argument::exact('users User'))->willReturn($this->users);
        $this->mysql->get(\Prophecy\Argument::exact('pipes_users PipeUser'))->willReturn($this->pipesUsers);

        $pipeUsers = $this->pipeUser->getAll();
        $this->assertEquals(1, $pipeUsers[0]['pipe_id']);
    }
}
