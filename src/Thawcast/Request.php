<?php
/**
 * Thawcast
 * @author Ross Masters <ross@rossmasters.com>
 */

namespace Thawcast;

use Thawcast\Thawcast;
use Requests,
    Requests_Auth_Basic;
use Exception;

/**
 * A request to an Icecast server
 *
 * @package Requests
 */
abstract class Request
{
    /**
     * @var Thawcast\Thawcast
     */
    protected $server;

    /**
     * Retrieve the data for this request, with a number of optional arguments
     *
     * An alternative method of usage is to construct the Request yourself and
     * call `$request->load($args)`.
     *
     * @param Thawcast $server Icecast server to use
     * @param array|null $args Arguments given (see documentation)
     * @return Thawcast\Response Specialised response inheriting from Response
     */
    public static function retrieve(Thawcast $server, array $args=array()) {
        $instance = new static($server);
        return $instance->load($args);
    }

    /**
     * Constructor
     * @param Thawcast Server instance to use
     */
    public function __construct(Thawcast $server) {
        $this->server = $server;
    }

    /**
     * Load the request data
     * @param array|null Request parameters
     * @return Thawcast\Response
     */
    public function load(array $args=array()) {
        return $this->buildResponse($args);
    }

    /**
     * Build the response (implemented by a Request type)
     * @param array|null Request parameters
     * @return Thawcast\Response
     */
    abstract protected function buildResponse(array $args);

    /**
     * Perform a GET request on the server
     * @param string $url
     * @param bool $requiresAuth Whether admin credentials should be sent
     * @return string Response body
     * @throws Exception If a 4xx or 5xx error is received
     */
    protected function get($url, $requiresAuth=false) {
        // Build the URL, with leading '/'
        $url = $this->server->getHost() . '/' . ltrim($url, '/');

        // Request headers to send
        $headers = array(
            'Accept: application/xml,text/xml',
            sprintf('User-Agent: %s', Thawcast::getUserAgent()),
        );

        // Request options
        $options = array();
        // Add credentials if the request needs to be authenticated
        if ($requiresAuth) {
            $options['auth'] = new Requests_Auth_Basic(
                array_values($this->server->getCredentials())
            );
        }

        $response = Requests::get($url, $headers, $options);

        if (!$response->success) {
            switch (substr($response->status_code, 0, 1)) {
            case 4:
                throw new Exception(sprintf('A bad request was made (%d): "%s"',
                    $response->status_code, $response->body));
            case 5:
                throw new Exception(sprintf('A server error occured (%d): "%s"',
                    $response->status_code, $response->body));
            }
        }

        return $response;
    }
}
