<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 18:48
 */

namespace Enhavo\Bundle\AppBundle\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBatchType extends AbstractBatchType
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * BaseBatchType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function createViewData(array $options, ViewData $data, ?ResourceInterface $resource = null)
    {
        $data->set('label', $this->getLabel($options));
        $data->set('confirmMessage', $this->getConfirmMessage($options));
        $data->set('position', $options['position']);
        $data->set('component', $options['component']);
    }

    private function getLabel($options)
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }

    private function getConfirmMessage($options)
    {
        if($options['confirm_message'] !== null) {
            return $this->translator->trans($options['confirm_message'], [], $options['translation_domain']);
        }
        return null;
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

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'permission'  => null,
            'position'  => 0,
            'translation_domain' => null,
            'hidden' => false,
            'confirm_message' => null,
            'component' => 'batch-url',
            'route' => null,
            'route_parameters' => null,
        ]);

        $resolver->setRequired(['label']);
    }

    public static function getParentType(): ?string
    {
        return null;
    }
}
