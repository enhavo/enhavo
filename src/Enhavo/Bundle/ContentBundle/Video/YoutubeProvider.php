<?php

namespace Enhavo\Bundle\ContentBundle\Video;

use Enhavo\Bundle\ContentBundle\Model\Video;

class YoutubeProvider implements ProviderInterface
{
    const PROVIDER_NAME = 'youtube';
    const IMAGE_TYPE_HQ = 'hqdefault';
    const IMAGE_TYPE_MAX_RESOLUTION = 'maxresdefault';

    /** @var ?string */
    private $apiKey;

    /** @var string */
    private $imageType;

    /**
     * @param string $apiKey
     */
    public function __construct(?string $apiKey = null, $imageType = self::IMAGE_TYPE_MAX_RESOLUTION)
    {
        $this->apiKey = $apiKey;
        $this->imageType = $imageType;
    }

    public function create(string $url): Video
    {
        $videoId = $this->getVideoId($url);

        if (empty($this->apiKey)) {
            return new Video(
                self::PROVIDER_NAME,
                '',
                '',
                sprintf('https://i.ytimg.com/vi/%s/%s.jpg', $videoId, $this->imageType),
                sprintf("http://www.youtube.com/watch?v=%s", $videoId),
                sprintf("http://www.youtube.com/embed/%s", $videoId)
            );
        }

        $hash = json_decode(file_get_contents(sprintf("https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=%s&key=%s", $videoId, $this->apiKey)));

        return new Video(
            self::PROVIDER_NAME,
            $hash->items[0]->snippet->title,
            str_replace(array("", "<br/>", "<br />"), NULL, $hash->items[0]->snippet->description),
            sprintf('https://i.ytimg.com/vi/%s/%s.jpg', $hash->items[0]->id, $this->imageType),
            sprintf("http://www.youtube.com/watch?v=%s", $hash->items[0]->id),
            sprintf("http://www.youtube.com/embed/%s", $hash->items[0]->id)
        );
    }

    private function getVideoId($url)
    {
        preg_match("/v=([^&#]*)/", parse_url($url, PHP_URL_QUERY), $videoId);
        return $videoId[1];
    }

    public function isSupported(string $url): bool
    {
        $host = explode('.', str_replace('www.', '', strtolower(parse_url($url, PHP_URL_HOST))))[0];

        return $host === 'youtube';
    }
}
