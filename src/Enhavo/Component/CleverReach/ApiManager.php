<?php

namespace Enhavo\Component\CleverReach;

use Enhavo\Component\CleverReach\Http\AdapterInterface as HttpAdapter;

class ApiManager implements ApiManagerInterface
{
    /** @var HttpAdapter */
    protected $adapter;

    /**
     * ApiManager constructor.
     *
     * @param HttpAdapter $adapter
     */
    public function __construct(HttpAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function createSubscriber(
        string $email,
        int $groupId,
        bool $active = false,
        array $attributes = [],
        array $globalAttributes = [],
        array $tags = [],
    ) {
        $now = time();

        return $this->adapter->action(
            'post',
            "/v3/groups.json/{$groupId}/receivers",
            [
                'email' => $email,
                'registered' => $now,
                'activated' => $active ? $now : 0,
                'attributes' => $attributes,
                'global_attributes' => $globalAttributes,
                'tags' => $tags,
            ]
        );
    }


    /**
     * @param string $email
     * @param int $groupId
     * @param array $attributes
     * @param array $globalAttributes
     * @param array $tags
     * @return mixed
     */
    public function updateSubscriber(
        string $email,
        int $groupId,
        array $attributes = [],
        array $globalAttributes = [],
        array $tags = [],
    ) {
        $now = time();

        return $this->adapter->action(
            'put',
            "/v3/groups.json/{$groupId}/receivers/{$email}",
            [
                'attributes' => $attributes,
                'global_attributes' => $globalAttributes,
                'tags' => $tags,
            ]
        );
    }

    /**
     * @param $email
     * @param int $groupId
     * @return mixed
     */
    public function activateSubscriber($email, int $groupId)
    {
        return $this->adapter->action('put', "/v3/groups.json/{$groupId}/receivers/{$email}/activate");
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscriber(string $email, int $groupId = null)
    {
        if ($groupId) {
            return $this->adapter->action('get', "/v3/groups.json/{$groupId}/receivers/{$email}");
        }

        return $this->adapter->action('get', "/v3/receivers.json/{$email}");
    }

    /**
     * {@inheritdoc}
     */
    public function setSubscriberStatus(string $email, int $groupId, $active = true)
    {
        if ($active) {
            return $this->adapter->action('put', "/v3/groups.json/{$groupId}/receivers/{$email}/activate");
        }

        return $this->adapter->action('put', "/v3/groups.json/{$groupId}/receivers/{$email}/deactivate");
    }

    /**
     * {@inheritdoc}
     */
    public function triggerDoubleOptInEmail(string $email, int $formId, array $options = [])
    {
        return $this->adapter->action(
            'post',
            "/v3/forms.json/{$formId}/send/activate",
            [
                'email' => $email,
                'doidata' => array_merge(
                    [
                        'user_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                        'referer' => $_SERVER['HTTP_REFERER'] ?? 'http://localhost',
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'FakeAgent/2.0 (Ubuntu/Linux)',
                    ],
                    $options
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function triggerDoubleOptOutEmail(string $email, int $formId, array $options = [])
    {
        return $this->adapter->action(
            'post',
            "/v3/forms.json/{$formId}/send/deactivate",
            [
                'email' => $email,
                'doidata' => array_merge(
                    [
                        'user_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                        'referer' => $_SERVER['HTTP_REFERER'] ?? 'http://localhost',
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'FakeAgent/2.0 (Ubuntu/Linux)',
                    ],
                    $options
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function deleteSubscriber(string $email, int $groupId)
    {
        return $this->adapter->action('delete', "/v3/groups.json/{$groupId}/receivers/{$email}");
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return $this->adapter->action('get', "/v3/groups.json");
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup(int $groupId)
    {
        return $this->adapter->action('get', "/v3/groups.json/{$groupId}");
    }

    /**
     * Returns the HTTP adapter.
     *
     * @return HttpAdapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
