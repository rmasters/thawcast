<?php
/**
 * Thawcast - Icecast analytics
 *
 * @author Ross Masters <ross@rossmasters.com>
 * @copyright Copyright (C) 2012 Ross Masters
 * @license MIT http://github.com/rmasters/thawcast/blob/master/LICENSE
 * @link http://github.com/rmasters/thawcast
 * @package Thawcast
 */

namespace Thawcast\Response;

use Thawcast\Response;
use SimpleXmlElement;

/**
 * Server information found in /admin/stats
 * 
 * @todo Implemented elements are docblocked
 * @package Responses
 */
class Stats extends Response
{
    /** @var string Hostname */
    protected $host;
    /** @var string Admin contact */
    protected $adminEmail;
    /** @var string Station/server location */
    protected $location;
    /** @var string Icecast version */
    protected $serverId;
    /** @var DateTime Server startup date/time */
    protected $serverStarted;

    protected $clients;
    protected $listeners;
    /** @var array List of sources */
    protected $sources;

    protected $connections;
    protected $clientConnections;
    protected $fileConnections;
    protected $listenerConnections;

    protected $sourceClientConnections;
    protected $sourceRelayConnections;
    protected $sourceTotalConnections;

    protected $stats;
    protected $statsConnections;

    public static function fromXml($xml) {
        $instance = new self;

        $icestats = new SimpleXmlElement($xml);

        // General server info
        $instance->host = (string) $icestats->host;
        $instance->adminEmail = (string) $icestats->admin;
        $instance->location = (string) $icestats->location;
        $instance->serverId = (string) $icestats->server_id;
        $instance->serverStarted = new \DateTime((string) $icestats->server_start);

        /**
         * Sources
         * @todo Some fields not always present:
         * Artist
         * Bitrate can be text
         * bitrate, audio_bitrate, channels, audio_channels etc.
         * @todo Need a canonical format output, or a non-strict model type
         */
        $instance->sources = array();
        foreach ($icestats->source as $source) {
            $mount = (string) $source['mount'];
            $instance->sources[$mount] = array(
                'audio_info' => (string) $source->audio_info,
                'bitrate' => (string) $source->bitrate,
                'genre' => (string) $source->genre,
                'ice-bitrate' => (int) $source->{'ice-bitrate'},
                'ice-channels' => (int) $source->{'ice-channels'},
                'ice-samplerate' => (int) $source->{'ice-samplerate'},
                'listener_peak' => (int) $source->listener_peak,
                'listeners' => (int) $source->listeners,
                'listenurl' => (string) $source->listenurl,
                'max_listeners' => $source->max_listeners == 'unlimited' ? 0 : (int) $source->max_listeners,
                'public' => (bool) $source->public,
                'server_description' => (string) $source->server_description,
                'server_name' => (string) $source->server_name,
                'server_type' => (string) $source->server_type,
                'slow_listeners' => (int) $source->slow_listeners,
                'source_ip' => (string) $source->source_ip,
                'stream_start' => new \DateTime((string) $source->stream_start),
                'title' => (string) $source->title,
                'total_bytes_read' => (string) $source->total_bytes_read,
                'total_bytes_sent' => (string) $source->total_bytes_sent,
            );
        }

        return $instance;
    }
}
