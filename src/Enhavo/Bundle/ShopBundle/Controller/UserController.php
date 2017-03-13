<?php
/**
 * UserController.php
 *
 * @since 13/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AppController
{
    public function addressAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);

        $template = $configuration->getTemplate('EnhavoShopBundle:Theme/User:address.html.twig');

        $em = $this->get('doctrine.orm.default_entity_manager');
        $repository = $em->getRepository('EnhavoShopBundle:UserAddress');
        $userAddress = $repository->findOneBy([
            'user' => $this->getUser()
        ]);

        $form = $this->createForm('enhavo_shop_user_address', $userAddress);

        if($request->isMethod('POST')) {
            $form->submit($request);
            if($form->isValid()) {
                $userAddress = $form->getData();
                $userAddress->setUser($this->getUser());
                $em->persist($userAddress);
                $em->flush();
            }
        }
        
        return $this->render($template, [
            'form' => $form->createView()
        ]);
    }
}