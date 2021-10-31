<?php

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\FormBundle\Form\Transformer\EntityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class AutoSuggestEntityType extends AbstractType
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * AutoSuggestEntityType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EntityTransformer(
            $this->em, $options['class'], $options['property'], $options['query_builder'], $options['factory']
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['route'] = $options['route'];
        $view->vars['suggestions'] = [];

        if (!$options['route']) {
            $queryBuilder = $options['query_builder'];
            $repository = $this->em->getRepository($options['class']);
            if ($queryBuilder instanceof \Closure) {
                /** @var QueryBuilder $qb */
                $qb = $queryBuilder->call($this, $repository);
                $entities = $qb->getQuery()->getResult();
            } else {
                $entities = $repository->findAll();
            }

            $propertyAccessor = new PropertyAccessor();
            foreach ($entities as $entity) {
                $view->vars['suggestions'][] = $propertyAccessor->getValue($entity, $options['property']);
            }
        }

        $view->vars['suggestions'] = array_unique($view->vars['suggestions']);
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'auto_suggest';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_builder' => null,
            'route' => null,
            'factory' => null
        ]);

        $resolver->setRequired('property');
        $resolver->setRequired('class');
    }
}
