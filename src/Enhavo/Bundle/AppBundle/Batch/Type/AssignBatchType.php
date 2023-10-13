<?php

namespace Enhavo\Bundle\AppBundle\Batch\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * AssignBatchType.php
 *
 * @since 02/01/20
 * @author gseidel
 */
class AssignBatchType extends AbstractBatchType
{
    /** @var RequestStack */
    private $requestStack;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var TranslatorInterface */
    private $translator;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * AssignBatchType constructor.
     * @param RequestStack $requestStack
     * @param FormFactoryInterface $formFactory
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $em
     */
    public function __construct(
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        TranslatorInterface $translator,
        EntityManagerInterface $em
    ) {
        $this->requestStack = $requestStack;
        $this->formFactory = $formFactory;
        $this->translator = $translator;
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $options, array $resources, ?ResourceInterface $resource = null): ?Response
    {
        $request = $this->requestStack->getMainRequest();
        $form = $this->formFactory->create($options['form']);

        $form->handleRequest($request);
        if(!$form->isValid()) {
            throw new BatchExecutionException($this->translator->trans($options['error_assign'], [], $options['translation_domain']));
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $data = $form->getData();

        if($options['data_property']) {
            $data = $propertyAccessor->getValue($data, $options['data_property']);
        }

        foreach($resources as $resource) {
            $propertyAccessor->setValue($resource, $options['property'], $data);
        }

        $this->em->flush();

        return null;
    }

    /**
     * @inheritdoc
     */
    public function createViewData(array $options, ViewData $data, ?ResourceInterface $resource = null)
    {
        $data['route'] = $options['route'];
        $data['routeParameters'] = $options['route_parameters'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'batch.assign.label',
            'translation_domain' => 'EnhavoAppBundle',
            'route' => null,
            'route_parameters' => null,
            'form_parameters' => [],
            'error_assign' => 'batch.assign.error.assign',
            'data_property' => null
        ]);

        $resolver->setRequired(['form', 'property']);
    }

    public static function getParentType(): ?string
    {
        return FormBatchType::class;
    }

    public static function getName(): ?string
    {
        return 'assign';
    }
}
