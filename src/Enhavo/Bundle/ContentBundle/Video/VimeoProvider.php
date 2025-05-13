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

use Enhavo\Bundle\ContentBundle\Model\Video;

class VimeoProvider implements ProviderInterface
{
    public const PROVIDER_NAME = 'vimeo';

    /** @var string */
    private $apiKey;

    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey;
    }

    public function create(string $url): Video
    {
        $videoId = $this->getVideoId($url);

        if (!empty($this->apiKey)) {
            $options = ['http' => [
                'method' => 'GET',
                'header' => sprintf('Authorization: Bearer %s', $this->apiKey),
            ]];
            $context = stream_context_create($options);
            $data = json_decode(file_get_contents(sprintf('https://api.vimeo.com/videos/%s', $videoId), false, $context));
        } else {
            $data = json_decode(file_get_contents(sprintf('https://vimeo.com/api/oembed.json?url=%s', $url), false));
        }

        return new Video(
            self::PROVIDER_NAME,
            $data->title,
            str_replace(['<br>', '<br/>', '<br />'], '', $data->description),
            $data->thumbnail_url,
            sprintf('https://vimeo.com/%s', $data->video_id),
            sprintf('https://player.vimeo.com/video/%s', $data->video_id),
        );
    }

    private function getVideoId($url)
    {
        return substr(parse_url($url, PHP_URL_PATH), 1);
    }

    public function isSupported(string $url): bool
    {
        $host = explode('.', str_replace('www.', '', strtolower(parse_url($url, PHP_URL_HOST))))[0];

        return 'vimeo' === $host;
    }
}
