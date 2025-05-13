<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\CleverReach\Http;

interface AdapterInterface
{
    /**
     * Creates a new access token.
     */
    public function authorize(string $clientId, string $clientSecret);

    /**
     * Returns the response data.
     */
    public function action(string $method, string $path, array $data = []);

    /**
     * Returns the access token.
     *
     * @return string|null
     */
    public function getAccessToken();
}
