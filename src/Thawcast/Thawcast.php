<?php
/**
 * Thawcast
 * @author Ross Masters <ross@rossmasters.com>
 */

namespace Thawcast;

use InvalidArgumentException, LengthException, DomainException;

/**
 * Holds the details of an icecast server
 */
class Thawcast
{
    /**
     * @var string The URL of the Icecast web admin site
     */
    private $host;

    /**
     * @var array|null Admin credentials, if provided
     */
    private $credentials;

    const VERSION = '0.1';

    /**
     * Constructor
     * @param string $host URL of the Icecast2 server (assumed port 80 if missing)
     * @param array|null $credentials Admin login in the form array(user, pass)
     * @throws InvalidArgumentException, LengthException
     */
    public function __construct($host, $credentials=null) {
        $this->setHost($host);

        // Set admin credentials
        if (!is_null($credentials)) {
            if (!is_array($credentials)) {
                throw new InvalidArgumentException('Credentials should be ' .
                    'supplied as an array of two strings');
            }

            if (count($credentials) != 2) {
                throw new LengthException('Credentials array should contain ' .
                    'a username and a password');
            }

            $credentials = array_values($credentials);
            $this->setCredentials($credentials[0], $credentials[1]);
        }
    }

    /**
     * Get the url of the Icecast web interface
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * Validate and set the Icecast url
     * @param string $url
     * @throws DomainException, InvalidArgumentException
     * @return $this
     */
    public function setHost($url) {
        $urlParts = parse_url($url);
        if (false === $urlParts) {
            throw new DomainException('Icecast URL is malformed.');
        }

        if (!array_key_exists('host', $urlParts)) {
            throw new InvalidArgumentException('Icecast URL has no host');
        }

        // Assume http if missing
        if (!array_key_exists('scheme', $urlParts)) {
            $urlParts['scheme'] = 'http';
        }

        // Default Icecast2 web interface port is 8000
        if (!array_key_exists('port', $urlParts)) {
            $urlParts['port'] = 8000;
        }

        if (!array_key_exists('path', $urlParts)) {
            $urlParts['path'] = '/';
        }

        // Reconstruct the URL, using the scheme, host, port and a path
        // HTTP credentials are omitted, as they should be given in setCredentials()
        $url = sprintf('%s://%s:%d/%s', $urlParts['scheme'], $urlParts['host'],
            $urlParts['port'], $urlParts['path']);

        // Ensure a trailing slash is not present
        $this->host = rtrim($url, '/');

        return $this;
    }

    /**
     * Get the admin credentials, or throw an Exception if not set
     * @return array
     * @throws Exception If credentials are not set
     */
    public function getCredentials() {
        if (!$this->hasCredentials()) {
            throw new Exception('This instance has no admin credentials.');
        }
        return $this->credentials;
    }

    /**
     * Store admin credentials to use for admin functions (optional)
     * @param string $username
     * @param string $password
     * @return $this
     */
    public function setCredentials($username, $password) {
        $this->credentials = array(
            'username' => (string) $username,
            'password' => (string) $password
        );

        return $this;
    }

    /**
     * Whether the instance has been provided with admin credentials
     * @return bool
     */
    public function hasCredentials() {
        return is_array($this->credentials);
    }

    /**
     * Get the Icecast host
     * @return string
     */
    public function __toString() {
        return (string) $this->host;
    }

    /**
     * Get the server statistics
     * @return Thawcast\Response\Stats
     */
    public function stats() {
        return Request\Stats::retrieve($this);
    }

    /**
     * Get a list of sources
     * @return Thawcast\Response\Mounts
     */
    public function mounts() {
        return Request\Mounts::retrieve($this);
    }

    /**
     * Get listeners for a source
     * @return Thawcast\Response\Clients
     */
    public function clients($source) {
        return Request\Clients::retrieve($this, array('mount' => $source));
    }

    /**
     * Get a user-agent to include in requests made
     * @return string
     */
    public static function getUserAgent() {
        return sprintf('Thawcast %s', self::VERSION);
    }
}
