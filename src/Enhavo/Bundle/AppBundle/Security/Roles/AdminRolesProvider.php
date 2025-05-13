<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Security\Roles;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author gseidel
 */
class AdminRolesProvider implements RolesProvider
{
    /**
     * @var array
     */
    private $roles = [];

    public function __construct($roles, TranslatorInterface $translator)
    {
        foreach ($roles as $role => $data) {
            if ($data['display']) {
                $this->roles[$translator->trans($data['label'], [], $data['translation_domain'])] = $data['role'];
            }
        }
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
