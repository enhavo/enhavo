<?php

namespace Enhavo\Bundle\ContentBundle\Video;

use Enhavo\Bundle\ContentBundle\Model\Video;

class VimeoProvider implements ProviderInterface
{
    const PROVIDER_NAME = 'vimeo';

    /** @var string */
    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey;
    }

    public function create(string $url): Video
    {
        $videoId = $this->getVideoId($url);

        if (!empty($this->apiKey)) {
            $options = array('http' => array(
                'method'  => 'GET',
                'header' => sprintf('Authorization: Bearer %s'. $this->apiKey)
            ));
            $context  = stream_context_create($options);
            $data = json_decode(file_get_contents(sprintf("https://api.vimeo.com/%s", $videoId),false, $context));
        } else {
            $data = json_decode(file_get_contents(sprintf("https://vimeo.com/api/v2/video/%s.json", $videoId),false));
        }

        return new Video(
            self::PROVIDER_NAME,
            $data[0]->title,
            str_replace(array("<br>", "<br/>", "<br />"), NULL, $data[0]->description),
            $data[0]->thumbnail_large,
            sprintf("https://vimeo.com/%s", $data[0]->id),
            sprintf("https://player.vimeo.com/video/%s", $data[0]->id),
            sprintf("https://vimeo.com/%s", $data[0]->id),
        );
    }

    private function getVideoId($url)
    {
        return substr(parse_url($url, PHP_URL_PATH), 1);
    }

    public function isSupported(string $url): bool
    {
        $host = explode('.', str_replace('www.', '', strtolower(parse_url($url, PHP_URL_HOST))))[0];

        return $host === 'vimeo';
    }
}
