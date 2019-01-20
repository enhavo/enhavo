<?php
/**
 * UserAddressWidget.php
 *
 * @since 13/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Widget\WidgetInterface;

class UserAddressWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'shop_user_address';
    }

    public function render($options)
    {
        $resolvedOptions = $this->resolveOptions([
            'template' => 'EnhavoShopBundle:Theme/Widget:user-address.html.twig'
        ], $options);

        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $repository = $em->getRepository('EnhavoShopBundle:UserAddress');
        $userAddress = $repository->findOneBy([
            'user' => $this->container->get('security.token_storage')->getToken()->getUser()
        ]);

        $form = $this->container->get('form.factory')->create('enhavo_shop_user_address', $userAddress);

        return $this->renderTemplate($resolvedOptions['template'], [
            'form' => $form->createView()
        ]);
    }
}
