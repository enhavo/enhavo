<?php

namespace Enhavo\Bundle\ContentBundle\Video;

use Enhavo\Bundle\ContentBundle\Model\Video;

class YoutubeProvider implements ProviderInterface
{
    const PROVIDER_NAME = 'youtube';
    const IMAGE_TYPE_HQ = 'hqdefault';
    const IMAGE_TYPE_MAX_RESOLUTION = 'maxresdefault';

    private ?string $apiKey;
    private ?string $imageType;

    public function __construct(?string $apiKey = null, string $imageType = self::IMAGE_TYPE_MAX_RESOLUTION)
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
                sprintf('https://www.youtube.com/watch?v=%s', $videoId),
                sprintf('https://www.youtube.com/embed/%s', $videoId),
            );
        }

        $hash = json_decode(file_get_contents(sprintf("https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=%s&key=%s", $videoId, $this->apiKey)));

        return new Video(
            self::PROVIDER_NAME,
            $hash->items[0]->snippet->title,
            str_replace(array("", "<br/>", "<br />"), NULL, $hash->items[0]->snippet->description),
            sprintf('https://i.ytimg.com/vi/%s/%s.jpg', $hash->items[0]->id, $this->imageType),
            sprintf('https://www.youtube.com/watch?v=%s', $hash->items[0]->id),
            sprintf('https://www.youtube.com/embed/%s', $hash->items[0]->id),
        );
    }

    private function getVideoId($url)
    {
        preg_match("/(v=|youtu\.be\/)([^&#]*)/", $url, $videoId);
        return count($videoId) > 2 ? $videoId[2] : null;
    }

    public function isSupported(string $url): bool
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST));

        return preg_match('/^(www\.)?(youtube\.com|youtu\.be)$/', $host);
    }
}
