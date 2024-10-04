<?php

namespace Enhavo\Bundle\NewsletterBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsletterStateColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        if(!$resource instanceof NewsletterInterface) {
            throw new \InvalidArgumentException;
        }

        $stateMap = [
            NewsletterInterface::STATE_CREATED => 'black',
            NewsletterInterface::STATE_PREPARED => 'orange',
            NewsletterInterface::STATE_SENDING  => 'orange',
            NewsletterInterface::STATE_SENT  => 'green'
        ];

        $data->set('value', $this->translator->trans(sprintf('newsletter.label.%s', $resource->getState()), [], 'EnhavoNewsletterBundle'));
        $data->set('color', $stateMap[$resource->getState()]);
    }

    public function createColumnViewData(array $options, Data $data): void
    {
        $data->set('wrap', true);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-state',
            'label' => 'newsletter.label.state',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'model' => 'StateColumn',
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter_state';
    }
}
