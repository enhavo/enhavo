<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-16
 * Time: 15:20
 */

namespace Enhavo\Bundle\CommentBundle\Form;

use Enhavo\Bundle\CommentBundle\Entity\Comment;
use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment', TextareaType::class, [
            'label' => 'comment.label.comment',
            'translation_domain' => 'EnhavoCommentBundle'
        ]);

        $builder->add('createdAt', DateTimeType::class, [
            'label' => 'comment.label.created_at',
            'translation_domain' => 'EnhavoCommentBundle'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class
        ]);
    }
}
