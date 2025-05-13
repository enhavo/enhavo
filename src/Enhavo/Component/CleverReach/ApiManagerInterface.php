<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\CleverReach;

interface ApiManagerInterface
{
    /**
     * Creates a subscriber.
     */
    public function createSubscriber(
        string $email,
        int $groupId,
        bool $active = false,
        array $attributes = [],
        array $globalAttributes = [],
    );

    /**
     * Returns a subscriber.
     */
    public function getSubscriber(string $email, ?int $groupId = null);

    /**
     * Sets the active status of a subscriber.
     *
     * @param bool $active
     */
    public function setSubscriberStatus(string $email, int $groupId, $active = true);

    /**
     * Triggers the Double-Opt-In email for a subscriber.
     */
    public function triggerDoubleOptInEmail(string $email, int $formId, array $options = []);

    /**
     * Triggers the Double-Opt-Out email for a subscriber.
     */
    public function triggerDoubleOptOutEmail(string $email, int $formId, array $options = []);

    /**
     * Deletes a subscriber.
     */
    public function deleteSubscriber(string $email, int $groupId);

    /**
     * Get group by given id
     */
    public function getGroup(int $groupId);

    /**
     * Retrieve a list of all groups.
     */
    public function getGroups();
}
