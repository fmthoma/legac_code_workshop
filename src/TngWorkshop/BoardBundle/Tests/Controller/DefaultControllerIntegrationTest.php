<?php

namespace TngWorkshop\BoardBundle\Tests\Controller;

use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TngWorkshop\BoardBundle\Service\BoardService;

/** @group unit */
class DefaultControllerIntegrationTest extends WebTestCase
{
    /** @var Client */
    private $client;

    /** @var BoardService|PHPUnit_Framework_MockObject_MockObject */
    private $mockService;


    public function setUp()
    {
        $this->client = static::createClient();

        $this->mockService = $this->getMockBuilder('TngWorkshop\BoardBundle\Service\BoardService')
            ->disableOriginalConstructor()->getMock();

        $this->client->getContainer()->set('board.board_service', $this->mockService);
    }


    public function testGetMessages()
    {
        $this->mockService->expects($this->once())
            ->method('getMessages')
            ->with(1, 10)
            ->willReturn(array());

        $this->mockService->expects($this->once())
            ->method('getAllTags')
            ->willReturn(array());

        $this->client->request('GET', '/');
    }

    public function testGetMessagesStartingAtPage()
    {
        $this->mockService->expects($this->once())
            ->method('getMessages')
            ->with(3, 10)
            ->willReturn(array());

        $this->mockService->expects($this->once())
            ->method('getAllTags')
            ->willReturn(array());

        $this->client->request('GET', '/?p=3');
    }

    public function testGetMessagesWithTag()
    {
        $this->mockService->expects($this->once())
            ->method('getMessagesWithTag')
            ->with('someTag')
            ->willReturn(array());

        $this->mockService->expects($this->once())
            ->method('getAllTags')
            ->willReturn(array());

        $this->client->request('GET', '/?tag=someTag');
    }

    public function testPostMessage()
    {
        $this->mockService->expects($this->once())
            ->method('postMessage')
            ->with('My Name', 'this is a #test #string');

        $this->mockService->expects($this->once())
            ->method('getMessages')
            ->with(1, 10)
            ->willReturn(array());

        $this->mockService->expects($this->once())
            ->method('getAllTags')
            ->willReturn(array());

        $this->client->request('POST', '/', array('user' => 'My Name', 'text' => 'this is a #test #string'));
    }
}
