<?php
/**
 * StatisticBlock.php
 *
 * @since 17/05/17
 * @author gseidel
 */

namespace Enhavo\Bundle\DashboardBundle\Block;

use Enhavo\Bundle\AppBundle\Type\AbstractType;

class StatisticBlock extends AbstractType
{
    public function render($parameters)
    {
        $providers = $this->getRequiredOption('providers', $parameters);
        $collector = $this->container->get('enhavo_dashboard.statistic_collector');

        $providerServices = [];
        foreach($providers as $provider) {
            $providerServices[] = $collector->getType($provider);
        }

        return $this->renderTemplate('EnhavoDashboardBundle:Block:statistic.html.twig', [
            'providers' => $providerServices
        ]);
    }

    public function getType()
    {
        return 'statistic';
    }
}