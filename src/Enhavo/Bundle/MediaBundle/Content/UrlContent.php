<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 10:29
 */

namespace Enhavo\Bundle\MediaBundle\Content;

use Enhavo\Bundle\MediaBundle\Exception\FileException;
use GuzzleHttp\Client;

class UrlContent extends AbstractContent
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $path;

    /**
     * @var boolean
     */
    private $loaded = false;

    public function __construct($url)
    {
        $this->url = $url;
        $tempPath = tempnam(sys_get_temp_dir(), 'Content');
        $this->path = $tempPath;
    }

    public function getContent()
    {
        $this->load();
        return file_get_contents($this->path);
    }

    public function getFilePath()
    {
        $this->load();
        return $this->path;
    }

    private function load()
    {
        if($this->loaded === true) {
            return;
        }

        $client = new Client();
        $response = $client->request('GET', $this->url);
        if($response->getStatusCode() != 200) {
            throw new FileException(sprintf('File could not be download from uri "%s".', $this->url));
        }

        file_put_contents($this->getFilePath(), $response->getBody()->getContents());
    }

    public function __unset()
    {
        if($this->loaded) {
            unlink($this->path);
        }
    }
}