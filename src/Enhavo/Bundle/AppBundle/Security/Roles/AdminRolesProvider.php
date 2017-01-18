<?php
/**
 * AdminRolesProvider.php
 *
 * @since 24/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Security\Roles;

use Symfony\Component\Translation\TranslatorInterface;

class AdminRolesProvider implements RolesProvider
{
    /**
     * @var array
     */
    protected $roles = array();

    public function __construct($roles, TranslatorInterface $translator)
    {
        foreach($roles as $role => $data) {
            if($data['display']) {
                $this->roles[$data['role']] = $translator->trans($data['label'], [], $data['translationDomain']);
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