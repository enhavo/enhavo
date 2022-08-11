<?php
/**
 * UserAddressType.php
 *
 * @since 13/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;

class UserAddressType extends AbstractResourceType
{
    public function getParent()
    {
        return AddressSubjectType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_user_address';
    }
}
