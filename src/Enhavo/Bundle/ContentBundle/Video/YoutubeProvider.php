<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Video;

use Enhavo\Bundle\ContentBundle\Exception\VideoException;
use Enhavo\Bundle\ContentBundle\Model\Video;

class YoutubeProvider implements ProviderInterface
{
    public const REGEX = '/(?:http:|https:)*?\/\/(?:www\.|)(?:youtube\.com|m\.youtube\.com|youtu\.|youtube-nocookie\.com).*(?:v=|v%3D|v\/|(?:a|p)\/(?:a|u)\/\d.*\/|watch\?|vi(?:=|\/)|\/embed\/|\/shorts\/|oembed\?|be\/|e\/)([^&?%#\/\n]*)/';
    public const PROVIDER_NAME = 'youtube';
    public const IMAGE_TYPE_MQ = 'mqdefault';
    public const IMAGE_TYPE_HQ = 'hqdefault';
    public const IMAGE_TYPE_SQ = 'sddefault';
    public const IMAGE_TYPE_MAX = 'maxresdefault';

    /** @var ?string */
    private $apiKey;

    /** @var string */
    private $imageType;

    /** @var bool */
    private $checkThumbnailUrl;

    public function __construct(?string $apiKey = null, $imageType = self::IMAGE_TYPE_MAX, $checkThumbnailUrl = true)
    {
        $this->apiKey = $apiKey;
        $this->imageType = $imageType;
        $this->checkThumbnailUrl = $checkThumbnailUrl;
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
                sprintf('https://www.youtube.com/watch?v=%s', $videoId),
                sprintf('https://www.youtube.com/embed/%s', $videoId)
            );
        }

        $hash = json_decode(file_get_contents(sprintf('https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=%s&key=%s', $videoId, $this->apiKey)));

        return new Video(
            self::PROVIDER_NAME,
            $hash->items[0]->snippet->title,
            str_replace(['', '<br/>', '<br />'], null, $hash->items[0]->snippet->description),
            $this->getThumbnailUrl($hash->items[0]->id, $this->imageType),
            sprintf('https://www.youtube.com/watch?v=%s', $hash->items[0]->id),
            sprintf('https://www.youtube.com/embed/%s', $hash->items[0]->id)
        );
    }

    private function getVideoId($url)
    {
        $result = preg_match(self::REGEX, $url, $matches);
        if ($result) {
            return $matches[1];
        }

        return null;
    }

    private function getThumbnailUrl($id, $preferredType)
    {
        if (!$this->checkThumbnailUrl) {
            return sprintf('https://i.ytimg.com/vi/%s/%s.jpg', $id, $preferredType);
        }

        $types = [self::IMAGE_TYPE_MQ, self::IMAGE_TYPE_SQ, self::IMAGE_TYPE_HQ, self::IMAGE_TYPE_MAX];
        $key = array_search($preferredType, $types);
        unset($types[$key]);

        $url = sprintf('https://i.ytimg.com/vi/%s/%s.jpg', $id, $preferredType);
        if ($this->checkUrl($url)) {
            return $url;
        }

        while ($type = array_pop($types)) {
            $url = sprintf('https://i.ytimg.com/vi/%s/%s.jpg', $id, $type);
            if ($this->checkUrl($url)) {
                return $url;
            }
        }

        throw new VideoException(sprintf('Can\' t resolve thumbnail image for youtube video with id: "%s"', $id));
    }

    private function checkUrl($url): bool
    {
        $headers = get_headers($url);

        return '200' == substr($headers[0], 9, 3);
    }

    public function isSupported(string $url): bool
    {
        return preg_match(self::REGEX, $url) > 0;
    }
}
