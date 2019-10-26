<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-16
 * Time: 15:20
 */

namespace Enhavo\Bundle\CommentBundle\Form\Type;

use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\FormBundle\Form\Type\AutoCompleteEntityType;
use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\UserBundle\Form\Type\UserAutoCompleteEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'comment.label.name',
            'translation_domain' => 'EnhavoCommentBundle'
        ]);

        $builder->add('email', TextType::class, [
            'label' => 'comment.label.email',
            'translation_domain' => 'EnhavoCommentBundle'
        ]);

        $builder->add('comment', TextareaType::class, [
            'label' => 'comment.label.comment',
            'translation_domain' => 'EnhavoCommentBundle'
        ]);

        $builder->add('createdAt', DateTimeType::class, [
            'label' => 'comment.label.created_at',
            'translation_domain' => 'EnhavoCommentBundle'
        ]);

        $builder->add('user', UserAutoCompleteEntityType::class, [
            'placeholder' => '---'
        ]);

        $builder->add('state', ChoiceType::class, [
            'label' => 'comment.label.state',
            'translation_domain' => 'EnhavoCommentBundle',
            'choices' => [
                'comment.label.pending' => CommentInterface::STATE_PENDING,
                'comment.label.publish' => CommentInterface::STATE_PUBLISH,
                'comment.label.deny' => CommentInterface::STATE_DENY
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass
        ]);
    }
}
