<?php

namespace Thawcast\Request;

use PHPUnit_Framework_TestCase;
use Thawcast\Thawcast;

class TestBase extends PHPUnit_Framework_TestCase
{
    protected $server;

    public function setUp() {
        $this->server = new Thawcast(ICECAST_HOST, array(ICECAST_ADMIN_USERNAME, ICECAST_ADMIN_PASSWORD));
    }
}
