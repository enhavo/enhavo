<?php
/**
 * RouteType.php
 *
 * @since 19/05/15
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\RouteType as AppRouteType;
use Symfony\Component\Form\FormBuilderInterface;

class RouteType extends AppRouteType
{
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('staticPrefix');
        
        $builder->add('staticPrefix', 'text', [
            'translation' => $this->translation
        ]);
    }
}