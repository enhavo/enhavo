<?php

namespace rdoepner\CleverReach;

use rdoepner\CleverReach\Http\AdapterInterface as HttpAdapter;

class ApiManager implements ApiManagerInterface
{
    /**
     * @var HttpAdapter
     */
    protected $httpAdapter;

    /**
     * ApiManager constructor.
     *
     * @param HttpAdapter $httpAdapter
     */
    public function __construct(HttpAdapter $httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->httpAdapter->authorize();
    }

    /**
     * {@inheritdoc}
     */
    public function createSubscriber(string $email, int $groupId, bool $active = false, array $attributes = [])
    {
        $now = time();

        return $this->httpAdapter->action(
            'post',
            "/v3/groups.json/{$groupId}/receivers",
            array_merge(
                [
                    'email' => $email,
                ],
                [
                    'registered' => $now,
                    'activated' => $active ? $now : 0,
                ],
                [
                    'attributes' => $attributes,
                ]
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscriber(string $email, int $groupId = null)
    {
        if ($groupId) {
            return $this->httpAdapter->action('get', "/v3/groups.json/{$groupId}/receivers/{$email}");
        }

        return $this->httpAdapter->action('get', "/v3/receivers.json/{$email}");
    }

    /**
     * {@inheritdoc}
     */
    public function deleteSubscriber(string $email, int $groupId = null)
    {
        if ($groupId) {
            return $this->httpAdapter->action('delete', "/v3/groups.json/{$groupId}/receivers/{$email}");
        }

        return $this->httpAdapter->action('delete', "/v3/receivers.json/{$email}");
    }
}
