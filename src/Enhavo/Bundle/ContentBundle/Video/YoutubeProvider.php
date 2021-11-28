<?php

namespace Enhavo\Bundle\ContentBundle\Video;

use Enhavo\Bundle\ContentBundle\Model\Video;

class YoutubeProvider implements ProviderInterface
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
        preg_match("/v=([^&#]*)/", parse_url($url, PHP_URL_QUERY), $video_id);
        $video_id = $video_id[1];
        $hash = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=".$video_id."&key=".$this->apiKey));

        return new Video(
            'YouTube',
            $hash->items[0]->snippet->title,
            str_replace(array("", "<br/>", "<br />"), NULL, $hash->items[0]->snippet->description),
            'https://i.ytimg.com/vi/'.$hash->items[0]->id.'/hqdefault.jpg',
            "http://www.youtube.com/watch?v=" . $hash->items[0]->id,
            "http://www.youtube.com/embed/" . $hash->items[0]->id
        );
    }

    public function isSupported(string $url): bool
    {
        $host = explode('.', str_replace('www.', '', strtolower(parse_url($url, PHP_URL_HOST))));
        $host = isset($host[0]) ? $host[0] : $host;

        return $host === 'youtube';
    }
}
