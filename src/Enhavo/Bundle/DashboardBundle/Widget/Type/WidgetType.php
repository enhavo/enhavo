<?php


namespace Enhavo\Bundle\DashboardBundle\Widget\Type;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\DashboardBundle\Widget\WidgetTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class WidgetType extends AbstractType implements WidgetTypeInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * WidgetType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function getPermission(array $options)
    {
        return $options['permission'];
    }

    /**
     * @inheritdoc
     */
    public function isHidden(array $options)
    {
        return $options['hidden'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => '',
            'translation_domain' => null,
            'hidden' => false,
            'permission' => null,
        ]);

        $resolver->setRequired([
            'component'
        ]);
    }

    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null)
    {
        $data['label'] = $this->translator->trans($options['label'], [], $options['translation_domain']);
        $data['component'] = $options['component'];
    }
}
