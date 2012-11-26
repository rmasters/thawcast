<?php

namespace Thawcast;

use PHPUnit_Framework_TestCase;

class ThawcastTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor() {
        // Non-admin privileged
        $thawcast = new Thawcast('http://localhost');
        $this->assertEquals('http://localhost:8000', $thawcast->getHost());
        $this->assertFalse($thawcast->hasCredentials());

        // Admin privileged
        $thawcast = new Thawcast('http://localhost', array('admin', 'password'));
        $this->assertTrue($thawcast->hasCredentials());
        $this->assertEquals(array('username' => 'admin', 'password' => 'password'), $thawcast->getCredentials());

        // Admin privileged (wrong number of credential elements)
        $this->setExpectedException('LengthException');
        $thawcast = new Thawcast('http://localhost', array('admin'));

        // Admin privileged (invalid credentials argument)
        $this->setExpectedException('InvalidArgumentException');
        $thawcast = new Thawcast('http://localhost', 'admin');
    }

    public function testHost() {
        // Without port
        $thawcast = new Thawcast('http://localhost');
        $this->assertEquals('http://localhost:8000', $thawcast->getHost());

        // With port
        $thawcast->setHost('http://localhost:1234');
        $this->assertEquals('http://localhost:1234', $thawcast->getHost());

        // Without scheme or host
        $this->setExpectedException('InvalidArgumentException');
        $thawcast->setHost('localhost');

        // Without scheme
        $thawcast->setHost('//localhost');
        $this->assertEquals('http://localhost:8000', $thawcast->getHost());

        // With HTTP credentials
        $thawcast->setHost('http://user:pass@localhost');
        $this->assertEquals('http://localhost:8000', $thawcast->getHost());

        // Thawcast::setHost($url) used to append slashes, ensure these are gone
        $thawcast = new Thawcast('http://localhost/');
        $this->assertTrue(substr($thawcast->getHost(), -1, 1) !== '/');

        // Malformed
        $this->setExpectedException('DomainException');
        $thawcast->setHost('@#!');
    }

    public function testAdminCredentials() {
        $thawcast = new Thawcast('http://localhost');

        $thawcast->setCredentials('admin', 'password');
        $this->assertEquals(array('username' => 'admin', 'password' => 'password'), $thawcast->getCredentials());
    }

    public function testToString() {
        $thawcast = new Thawcast('http://localhost');
        $this->assertEquals('http://localhost:8000', (string) $thawcast);
    }
}
