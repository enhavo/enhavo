<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Action;

use Enhavo\Bundle\AppBundle\Util\ArrayUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractUrlActionType extends AbstractActionType
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * CreateAction constructor.
     * @param TranslatorInterface $translator
     * @param ActionLanguageExpression $actionLanguageExpression
     * @param RouterInterface $router
     */
    public function __construct(TranslatorInterface $translator, ActionLanguageExpression $actionLanguageExpression, RouterInterface $router)
    {
        parent::__construct($translator, $actionLanguageExpression);
        $this->router = $router;
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $data = ArrayUtil::merge($data, [
            'url' => $this->getUrl($options, $resource)
        ]);

        return $data;
    }

    protected function getUrl(array $options, $resource = null)
    {
        $parameters = [];

        if($options['append_id'] && $resource !== null && $resource->getId() !== null) {
            $parameters[$options['append_key']] = $resource->getId();
        }

        $parameters = array_merge_recursive($parameters, $options['route_parameters']);

        return $this->router->generate($options['route'], $parameters);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'route_parameters' => [],
            'append_id' => false,
            'append_key' => 'id'
        ]);

        $resolver->setRequired([
            'route',
        ]);
    }
}
