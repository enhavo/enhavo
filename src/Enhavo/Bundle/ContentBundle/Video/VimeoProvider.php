<?php

namespace Enhavo\Bundle\ContentBundle\Video;

use Enhavo\Bundle\ContentBundle\Model\Video;

class VimeoProvider implements ProviderInterface
{
    /** @var string */
    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function create(string $url): Video
    {
        $video_id = substr(parse_url($url, PHP_URL_PATH), 1);
        $hash = json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$video_id}.json"));

        return new Video(
            'Vimeo',
            $hash[0]->title,
            str_replace(array("<br>", "<br/>", "<br />"), NULL, $hash[0]->description),
            $hash[0]->thumbnail_large,
            "https://vimeo.com/" . $hash[0]->id,
            "https://player.vimeo.com/video/" . $hash[0]->id
        );
    }

    public function isSupported(string $url): bool
    {
        $host = explode('.', str_replace('www.', '', strtolower(parse_url($url, PHP_URL_HOST))));
        $host = isset($host[0]) ? $host[0] : $host;

        return $host === 'vimeo';
    }
}
