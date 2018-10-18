<?php

namespace rdoepner\CleverReach\Http;

interface AdapterInterface
{
    /**
     * Authorizes by credentials
     *
     * @return mixed
     */
    public function authorize();

    /**
     * Returns the response data
     *
     * @param string $method
     * @param string $path
     * @param array  $data
     *
     * @return mixed
     */
    public function action(string $method, string $path, array $data = []);

    /**
     * Returns the adapter config
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getConfig($key = null);
}
