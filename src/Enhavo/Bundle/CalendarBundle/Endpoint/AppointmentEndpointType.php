<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\CalendarBundle\Model\AppointmentInterface;
use Enhavo\Bundle\CalendarBundle\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly AppointmentRepository $repository,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var AppointmentInterface $resource */
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

        $context->set('resource', $resource);
        $data->set('resource', $this->normalize($resource, null, ['groups' => ['endpoint']]));
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'preview' => false,
            'template' => '{{ area }}/resource/appointment/show.html.twig',
            'resource' => null,
            'find_by' => 'id',
        ]);
    }
}
