<?php

namespace Thawcast\Request;

use PHPUnit_Framework_TestCase;
use Thawcast\Thawcast;

class TestBase extends PHPUnit_Framework_TestCase
{
    protected $server;

    public function setUp() {
        $icecastConfigAvailable = defined('ICECAST_HOST') &&
            defined('ICECAST_ADMIN_USERNAME') &&
            defined('ICECAST_ADMIN_PASSWORD');

        if (!$icecastConfigAvailable) {
            $this->markTestSkipped('Icecast configuration was not available.');
        } else {
            $this->server = new Thawcast(ICECAST_HOST,
                array(ICECAST_ADMIN_USERNAME, ICECAST_ADMIN_PASSWORD));
        }
    }
}
