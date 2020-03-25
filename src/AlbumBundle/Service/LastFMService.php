<?php


namespace AlbumBundle\Service;


use AlbumBundle\Controller\LastFMMethod;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LastFMService
 * @package AlbumBundle\Helper
 */
class LastFMService
{
    /** @const string  */
    const LAST_FM_API = "http://ws.audioscrobbler.com";

    /** @const string  */
    const JSON_FORMAT = "json";

    /** @const string  */
    const LAST_FM_API_KEY = "f63ac290937d369e1ac20dceab124169";

    /** @const string  */
    const API_VERSION = "/2.0/";

    /** @var ContainerInterface */
    private $container;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * LastFMService constructor.
     *
     * @param ContainerInterface $container
     * @param EntityManagerInterface $em
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * @param array $options
     *
     * @return mixed
     */
    private function consumeMusic(array $options) {

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => self::LAST_FM_API,
            // the default time out
            'timeout' => 2.0,
            'default' => [
                // a returns response even if there is a failure on the server
                'exceptions' => false,
            ]]);

        $response = $client->get(self::API_VERSION, $options);

        $jsonData = $response->getBody()->getContents();

        var_dump($jsonData, $options);die;
        return json_decode($jsonData, true);
    }

    /**
     * @param string $artist
     * @param string $track
     */
    public function getTrackData($artist, $track)
    {
        $options = [
            'query' => [
                'method' => LastFMMethod::TRACK_INFO,
                'artist' => $artist,
                'track' => $track,
                'api_key' => self::LAST_FM_API_KEY,
                'format' => self::JSON_FORMAT
            ]
        ];

        $this->consumeMusic($options);
    }

    /**
     * @param string $name
     * @param integer $limit
     *
     * @return mixed
     */
    public function searchAlbums($name, $limit)
    {
        $options = [
            'query' => [
                'method' => 'album.search',
                'api_key' => self::LAST_FM_API_KEY,
                'album' => $name,
                'limit' => $limit,
                'format' => self::JSON_FORMAT
            ]
        ];

        return $this->consumeMusic($options);
    }

    /**
     * @param $albumName
     * @param $artistName
     * @return mixed
     */
    public function getAlbumInfo($albumName, $artistName)
    {
        $options = [
            'query' => [
                'method' => LastFMMethod::ALBUM_INFO,
                'api_key' => self::LAST_FM_API_KEY,
                'artist' => $artistName,
                'album' => $albumName,
                'format' => self::JSON_FORMAT
            ]
        ];

        return $this->consumeMusic($options);
    }
}