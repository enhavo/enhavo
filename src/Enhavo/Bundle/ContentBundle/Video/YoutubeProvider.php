<?php

namespace Enhavo\Bundle\ContentBundle\Video;

use Enhavo\Bundle\ContentBundle\Exception\VideoException;
use Enhavo\Bundle\ContentBundle\Model\Video;

class YoutubeProvider implements ProviderInterface
{
    const PROVIDER_NAME = 'youtube';
    const IMAGE_TYPE_MQ = 'mqdefault';
    const IMAGE_TYPE_HQ = 'hqdefault';
    const IMAGE_TYPE_SQ = 'sddefault';
    const IMAGE_TYPE_MAX = 'maxresdefault';

    /** @var ?string */
    private $apiKey;

    /** @var string */
    private $imageType;

    /**
     * @param string $apiKey
     */
    public function __construct(?string $apiKey = null, $imageType = self::IMAGE_TYPE_MAX)
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
                $this->getThumbnailUrl($videoId, $this->imageType),
                sprintf("https://www.youtube.com/watch?v=%s", $videoId),
                sprintf("https://www.youtube.com/embed/%s", $videoId)
            );
        }

        $hash = json_decode(file_get_contents(sprintf("https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=%s&key=%s", $videoId, $this->apiKey)));

        return new Video(
            self::PROVIDER_NAME,
            $hash->items[0]->snippet->title,
            str_replace(array("", "<br/>", "<br />"), NULL, $hash->items[0]->snippet->description),
            $this->getThumbnailUrl($hash->items[0]->id, $this->imageType),
            sprintf("https://www.youtube.com/watch?v=%s", $hash->items[0]->id),
            sprintf("https://www.youtube.com/embed/%s", $hash->items[0]->id)
        );
    }

    private function getVideoId($url)
    {
        preg_match("/(v=|youtu\.be\/)([^&#]*)/", $url, $videoId);
        return count($videoId) > 2 ? $videoId[2] : null;
    }

    private function getThumbnailUrl($id, $preferredType)
    {
        $types = [self::IMAGE_TYPE_MQ, self::IMAGE_TYPE_SQ, self::IMAGE_TYPE_HQ, self::IMAGE_TYPE_MAX];
        $key = array_search($preferredType, $types);
        unset($types[$key]);

        $url = sprintf('https://i.ytimg.com/vi/%s/%s.jpg', $id, $preferredType);
        if ($this->checkUrl($url)) {
            return $url;
        }

        while($type = array_pop($types)) {
            $url = sprintf('https://i.ytimg.com/vi/%s/%s.jpg', $id, $type);
            if ($this->checkUrl($url)) {
                return $url;
            }
        }

        throw new VideoException('Can\' t resolve thmbnail image for youtube video with id: "%s"', $id);
    }

    private function checkUrl($url): bool
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3) == "200";
    }

    public function isSupported(string $url): bool
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST));

        return preg_match('/^(www\.)?(youtube\.com|youtu\.be)$/', $host);
    }
}
