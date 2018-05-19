<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.05.18
 * Time: 15:17
 */

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Form\Type\NodeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationPageNodeType extends AbstractType
{
    /**
     * @var string
     */
    private $pageClass;

    public function __construct($pageClass)
    {
        $this->pageClass = $pageClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', EntityType::class, [
            'class' => $this->pageClass,
            'label' => 'page.label.page',
            'translation_domain' => 'EnhavoPageBundle'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Node::class,
        ]);
    }

    public function getParent()
    {
        return NodeType::class;
    }

    public function getName()
    {
        return 'enhavo_navigation_submenu';
    }
}