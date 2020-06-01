<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-01
 * Time: 10:50
 */

namespace Enhavo\Bundle\NewsletterBundle\Controller;


use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class TrackingController extends AbstractController
{
    public function openAction(Request $request)
    {
        $token = $request->get('token');

        $repository = $this->getDoctrine()->getRepository(Receiver::class);

        /** @var Receiver $receiver */
        $receiver = $repository->findOneBy([
            'token' => $token
        ]);

        $response = new BinaryFileResponse(sprintf('%s/../Resources/image/pixel.png', __DIR__));

        if($receiver !== null) {
            $receiver->trackOpen();
            $this->getDoctrine()->getManager()->flush();
        }

        return $response;
    }
}
