<?php
/**
 * Thawcast
 * @author Ross Masters <ross@rossmasters.com>
 */

namespace Thawcast\Request;

use Thawcast\Request;
use Thawcast\Response\Clients as Response;

/**
 * Retrieve a list of clients for each mount
 *
 * @package Requests
 */
class Clients extends Request
{
    /**
     * Send a request and build the response
     * @param array $args
     * @return Thawcast\Response\Stats
     */
    protected function buildResponse(array $args) {
        if (!array_key_exists('mount', $args)) {
            throw new \Exception('Mount name is required');
        }

        $url = sprintf('/admin/listclients?mount=%s', $args['mount']);

        $response = $this->get($url, true);
        return Response::fromXml($response->body);
    }
}
