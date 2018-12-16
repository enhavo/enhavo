<?php
/**
 * AdminRolesProvider.php
 *
 * @since 24/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Security\Roles;

use Symfony\Contracts\Translation\TranslatorInterface;

class AdminRolesProvider implements RolesProvider
{
    /**
     * @var array
     */
    private $roles = array();

    public function __construct($roles, TranslatorInterface $translator)
    {
        foreach($roles as $role => $data) {
            if($data['display']) {
                $this->roles[$translator->trans($data['label'], [], $data['translationDomain'])] = $data['role'];
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