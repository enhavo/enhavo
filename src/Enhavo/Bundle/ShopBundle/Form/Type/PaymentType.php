<?php
/**
 * PaymentType.php
 *
 * @since 18/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var string
     */
    private $paymentClass;

    public function __construct($dataClass, $paymentClass)
    {
        $this->dataClass = $dataClass;
        $this->paymentClass = $paymentClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('method', 'entity',[
                'class' => $this->paymentClass,
                'choice_label' => 'code',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_shop_payment';
    }
}