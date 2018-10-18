<?php

namespace rdoepner\CleverReach;

interface ApiManagerInterface
{
    /**
     * Creates a new access token
     *
     * @return mixed
     */
    public function getAccessToken();

    /**
     * Creates a subscriber by group and email
     *
     * @param int    $groupId
     * @param string $email
     * @param bool   $active
     * @param array  $attributes
     *
     * @return mixed
     */
    public function createSubscriber(int $groupId, string $email, bool $active = false, array $attributes = []);

    /**
     * Returns a subscriber by group and email
     *
     * @param int    $groupId
     * @param string $email
     *
     * @return mixed
     */
    public function getSubscriber(int $groupId, string $email);

    /**
     * Deletes a subscriber by group and email
     *
     * @param int    $groupId
     * @param string $email
     *
     * @return mixed
     */
    public function deleteSubscriber(int $groupId, string $email);
}
