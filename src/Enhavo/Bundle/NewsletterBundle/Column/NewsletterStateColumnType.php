<?php

namespace Enhavo\Bundle\NewsletterBundle\Column;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterStateColumnType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
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

        $translator = $this->container->get('translator');
        return [
            'value' => $translator->trans(sprintf('newsletter.label.%s', $resource->getState()), [], 'EnhavoNewsletterBundle'),
            'color' => $stateMap[$resource->getState()]
        ];
    }

    public function createColumnViewData(array $options)
    {
        $data = parent::createColumnViewData($options);

        $data = array_merge($data, [
            'wrap' => true
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-state',
        ]);
    }

    public function getType()
    {
        return 'newsletter_state';
    }
}
