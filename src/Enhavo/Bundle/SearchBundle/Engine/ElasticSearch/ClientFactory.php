<?php

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Elastica\Client;

class ClientFactory
{
    public function create($dsn): ?Client
    {
        if (!str_starts_with($dsn, 'elastic://')) {
            return null;
        }

        $parts = parse_url($dsn);

        $config = [];
        if (isset($parts['host'])) {
            $config['host'] = $parts['host'];
        }

        if (isset($parts['port'])) {
            $config['port'] = $parts['port'];
        }

        return new Client($config);
    }

    public function getIndexName($dsn): ?string
    {
        if (!str_starts_with($dsn, 'elastic://')) {
            return null;
        }

        $parts = parse_url($dsn);
        if (!isset($parts['path'])) {
            throw  new \Exception('The path must be defined for search dsn');
        }
        return substr($parts['path'], 1);
    }
}
