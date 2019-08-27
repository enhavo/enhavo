<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterType;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends ResourceController
{
    use TemplateTrait;

    public function showAction(Request $request): Response
    {
        $slug = $request->get('slug');
        if (empty($slug)) {
            throw $this->createNotFoundException();
        }

        /** @var Newsletter $contentDocument */
        $contentDocument = $this->get('enhavo_newsletter.repository.newsletter')->findOneBy([
            'slug' => $slug
        ]);

        if (!$contentDocument) {
            throw $this->createNotFoundException();
        }
        return $this->showResourceAction($contentDocument);
    }

    public function showResourceAction(Newsletter $contentDocument)
    {
        $manager = $this->get(NewsletterManager::class);
        return new Response($manager->render($contentDocument));
    }

    public function sendAction(Request $request)
    {
        $id = $request->get('id');

        $currentNewsletter = $this->getDoctrine()
            ->getRepository('EnhavoNewsletterBundle:Newsletter')
            ->find($id);

        if (!$currentNewsletter) {
            $currentNewsletter = new Newsletter();
        }
        $group = $currentNewsletter->getGroup();

        if(!$group) {
            return new JsonResponse([
                'type' => 'error',
                'message' => $this->get('translator')->trans('newsletter.action.send.error.no_group', [], 'EnhavoNewsletterBundle')
            ], 400);
        }

        $manager = $this->get('enhavo.newsletter.newsletter_manager');
        $manager->prepareReceiver($currentNewsletter, $group);

        $currentNewsletter->setSent(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($currentNewsletter);
        $em->flush();

        return new JsonResponse([
            'type' => 'success',
            'message' => $this->get('translator')->trans('newsletter.action.send.success', [], 'EnhavoNewsletterBundle')
        ], 200);
    }

    public function testAction(Request $request)
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->appEventDispatcher->dispatchInitEvent(ResourceEvents::INIT_PREVIEW, $configuration);

        if($request->query->has('id')) {
            $request->attributes->set('id', $request->query->get('id'));
            /** @var NewsletterInterface|ResourceInterface $resource */
            $resource = $this->singleResourceProvider->get($configuration, $this->repository);
            $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        } else {
            /** @var NewsletterInterface|ResourceInterface $resource */
            $resource = $this->newResourceFactory->create($configuration, $this->factory);
            $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        }

        $form = $this->resourceFormFactory->create($configuration, $resource);
        $form->handleRequest($request);

        $manager = $this->get(NewsletterManager::class);
        $success = $manager->sendTest($resource, $request->get('email'));

        if($success) {
            return new JsonResponse([
                'type' => 'success',
                'message' => $this->get('translator')->trans('newsletter.action.test_mail.success', [], 'EnhavoNewsletterBundle')
            ]);
        } else {
            return new JsonResponse([
                'type' => 'error',
                'message' => $this->get('translator')->trans('newsletter.action.test_mail.error', [], 'EnhavoNewsletterBundle')
            ]);
        }
    }
}
