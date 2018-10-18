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
    public function createSubscriber(int $groupId, string $email, bool $active = false, array $attributes = [])
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
    public function getSubscriber(int $groupId, string $email)
    {
        return $this->httpAdapter->action('get', "/v3/groups.json/{$groupId}/receivers/{$email}");
    }

    /**
     * {@inheritdoc}
     */
    public function deleteSubscriber(int $groupId, string $email)
    {
        return $this->httpAdapter->action('delete', "/v3/groups.json/{$groupId}/receivers/{$email}");
    }
}
