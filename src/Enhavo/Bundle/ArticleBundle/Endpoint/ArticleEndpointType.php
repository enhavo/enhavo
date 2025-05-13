<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Method;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\ArticleBundle\Model\ArticleInterface;
use Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository;
use Enhavo\Bundle\CommentBundle\Comment\CommentManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ArticleRepository $repository,
        private readonly CommentManager $commentManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var ArticleInterface $resource */
        $resource = $options['resource'];

        if (null === $resource) {
            $findValue = $request->get($options['find_by']);
            $resource = $this->repository->findOneBy([
                $options['find_by'] => $findValue,
            ]);
        }

        if (null === $resource) {
            throw $this->createNotFoundException();
        }

        if (!$resource->isPublished() && !$options['preview']) {
            throw $this->createNotFoundException();
        }

        $commentContext = $this->commentManager->handleSubmitForm($request, $resource);
        if ($commentContext->isInsert()) {
            $context->setResponse(new RedirectResponse($request->getRequestUri()));
        }

        $context->set('resource', $resource);
        $data->set('resource', $this->normalize($resource, null, ['groups' => ['endpoint']]));
        $data->set('commentForm', $this->normalize($commentContext->getForm(), null, ['groups' => ['endpoint']]));
    }

    public function describe($options, Path $path)
    {
        $path
            ->method(Method::GET)
                ->description('Article data')
                ->summary('Get single article data')
                ->parameter('id')
                    ->in('path')
                    ->description('The article id')
                    ->required(true)
                ->end()
            ->end()
        ;
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'preview' => false,
            'template' => '{{ area }}/resource/article/show.html.twig',
            'resource' => null,
            'find_by' => 'id',
        ]);
    }
}
