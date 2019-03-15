<?php

namespace rdoepner\CleverReach\Http;

interface AdapterInterface
{
    /**
     * Creates a new access token.
     *
     * @param string $clientId
     * @param string $clientSecret
     *
     * @return mixed
     */
    public function authorize(string $clientId, string $clientSecret);

    /**
     * Returns the response data.
     *
     * @param string $method
     * @param string $path
     * @param array  $data
     *
     * @return mixed
     */
    public function action(string $method, string $path, array $data = []);

    /**
     * Returns the access token.
     *
     * @return string|null
     */
    public function getAccessToken();

    /**
     * Returns the adapter config.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getConfig($key = null);
}
