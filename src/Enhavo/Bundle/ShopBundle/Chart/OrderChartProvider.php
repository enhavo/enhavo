<?php
/**
 * OrderChartProvider.php
 *
 * @since 21/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Chart;

use Enhavo\Bundle\AppBundle\Chart\ChartProviderInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Repository\OrderRepository;

class OrderChartProvider extends AbstractType implements ChartProviderInterface
{
    /**
     * @var OrderRepository
     */
    protected $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getOptions($options = [])
    {
        return [];
    }

    public function getApp($options = [])
    {
        return 'shop/Chart/OrderChart';
    }

    public function getChartType($options = [])
    {
        return 'line';
    }

    public function getData($options = [])
    {
        $to = new \DateTime();
        $from = clone $to;
        $from->modify('-6 months');
        $from->modify('first day of this month');
        $from->setTime(0,0,0);
        $data = $this->getDataFromTo($from, $to);

        return [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'label' => $this->container->get('translator')->trans('label.sales', [], 'EnhavoShopBundle'),
                    'fill' => false,
                    'lineTension' => 0.1,
                    'backgroundColor' => "rgba(75,192,192,0.4)",
                    'borderColor' => "rgba(75,192,192,1)",
                    'borderCapStyle' => 'butt',
                    'borderDash' => [],
                    'borderDashOffset' => 0.0,
                    'borderJoinStyle' => 'miter',
                    'pointBorderColor' => "rgba(75,192,192,1)",
                    'pointBackgroundColor' => '#fff',
                    'pointBorderWidth' => 1,
                    'pointHoverRadius' => 5,
                    'pointHoverBackgroundColor' => "rgba(75,192,192,1)",
                    'pointHoverBorderColor' => "rgba(220,220,220,1)",
                    'pointHoverBorderWidth' => 2,
                    'pointRadius' => 1,
                    'pointHitRadius' => 10,
                    'data' => $data['data'],
                    'spanGaps' => false,
                ]
            ]
        ];
    }

    protected function getDataFromTo(\DateTime $from, \DateTime $to)
    {
        /** @var \DateTime[] $steps */
        $steps = [];
        $stepDate = clone $from;
        $steps[] = $stepDate;
        while($stepDate < $to) {
            $stepDate = clone $stepDate;
            $stepDate->modify('+1 month');
            if($stepDate < $to) {
                $steps[] = $stepDate;
            }
        }

        $labels = [];
        foreach($steps as $stepDate) {
            $labels[] = $this->container->get('translator')->trans($stepDate->format('F'));
        }

        $data = [];
        foreach($steps as $stepDate) {
            $data[] = $this->getOrderForMonth($stepDate);
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    protected function getOrderForMonth(\DateTime $date)
    {
        $from = $date;
        $to = clone $from;
        $to->modify('last day of this month');
        $to->setTime(23,59,59);

        /** @var OrderInterface[] $orders */
        $orders = $this->repository->findBetween('orderedAt', $from, $to, [
            'paymentState' => 'completed'
        ]);

        $totals = 0;
        foreach($orders as $order) {
            $totals += $order->getTotal();
        }
        return $totals/100;
    }

    public function getType()
    {
        return 'shop_order';
    }
}