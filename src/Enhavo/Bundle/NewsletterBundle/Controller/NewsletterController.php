<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterEmailType;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->showResourceAction($contentDocument, $request);
    }

    public function showResourceAction(Newsletter $contentDocument, Request $request)
    {
        $manager = $this->get(NewsletterManager::class);

        $token = $request->get('token');
        if ($token) {
            $receiver = $this->getDoctrine()->getRepository(Receiver::class)->findOneBy([
                'token' => $token
            ]);

            if ($receiver instanceof Receiver && $receiver->getNewsletter() === $contentDocument) {
                $content = $manager->render($receiver);
                return new Response($content);
            }
        }

        $content = $manager->renderPreview($contentDocument);
        return new Response($content);
    }

    public function sendAction(Request $request)
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        /** @var NewsletterInterface $newsletter */
        $newsletter = $this->singleResourceProvider->get($configuration, $this->repository);

        if ($newsletter->isPrepared()) {
            return new JsonResponse([
                'type' => 'error',
                'message' => $this->get('translator')->trans('newsletter.action.send.error.already_sent', [], 'EnhavoNewsletterBundle')
            ], 400);
        }

        $newsletterManager = $this->get(NewsletterManager::class);
        $newsletterManager->prepare($newsletter);

        return new JsonResponse([
            'type' => 'success',
            'message' => $this->get('translator')->trans('newsletter.action.send.success', [], 'EnhavoNewsletterBundle')
        ], 200);
    }

    public function testAction(Request $request)
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if ($request->query->has('id')) {
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
        $submittedFormData = [];
        parse_str($request->get('form'), $submittedFormData);
        $form->submit(isset($submittedFormData[$form->getName()]) ? $submittedFormData[$form->getName()] : []);

        $emailForm = $this->createForm(NewsletterEmailType::class);
        $emailForm->handleRequest($request);

        if (!$emailForm->isValid()) {
            return new JsonResponse([
                'type' => 'error',
                'message' => $this->get('translator')->trans('newsletter.action.test_mail.invalid', [], 'EnhavoNewsletterBundle')
            ], 400);
        }

        $manager = $this->get(NewsletterManager::class);
        $success = $manager->sendTest($resource, $emailForm->getData()['email']);

        if ($success) {
            return new JsonResponse([
                'type' => 'success',
                'message' => $this->get('translator')->trans('newsletter.action.test_mail.success', [], 'EnhavoNewsletterBundle')
            ]);
        } else {
            return new JsonResponse([
                'type' => 'error',
                'message' => $this->get('translator')->trans('newsletter.action.test_mail.error', [], 'EnhavoNewsletterBundle')
            ], 400);
        }
    }

    public function statsAction(Request $request)
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        /** @var NewsletterInterface $newsletter */
        $newsletter = $this->singleResourceProvider->get($configuration, $this->repository);

        $view = $this->viewFactory->create('app', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $newsletter,
            'template' => 'admin/resource/newsletter/stats.html.twig'
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }
}
