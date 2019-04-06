<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Action;

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
     * @param RouterInterface $router
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        parent::__construct($translator);
        $this->router = $router;
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options);

        $data = array_merge($data, [
            'url' => $this->getUrl($options, $resource)
        ]);

        return $data;
    }

    protected function getUrl(array $options, $resource = null)
    {
        return $this->router->generate($options['route'], $options['route_parameters']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'route_parameters' => []
        ]);

        $resolver->setRequired([
            'route',
        ]);
    }
}
