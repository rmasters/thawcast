<?php

namespace Thawcast\Request;

use Requests_Response;

class StatsTest extends TestBase
{
    public function testRequest() {
        $response = new Requests_Response;
        $response->status_code = 200;
        $response->body = file_get_contents(__DIR__ . '/examples/admin_stats.xml');

        // Mock the parent Thawcast\Request::get() method
        $stub = $this->getMock('Thawcast\Request\Stats', array('get'), array($this->server));
        $stub->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/admin/stats'), $this->equalTo(true))
            ->will($this->returnValue($response));

        $result = $stub->load();
    }
}
