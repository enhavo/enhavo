<?php
/**
 * SyliusRouteBuilder.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Builder\Route;

class SyliusRouteBuilder extends AbstractRouteBuilder
{
    /**
     * @var string
     */
    private $paginate;

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $sorting;

    /**
     * @return string
     */
    public function getPaginate()
    {
        return $this->paginate;
    }

    /**
     * @param string $paginate
     */
    public function setPaginate($paginate)
    {
        $this->paginate = $paginate;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return array
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param array $sorting
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }

    protected function processDefaults($defaults)
    {
        $defaults = parent::processDefaults($defaults);

        $sylius = array();

        if($this->template) {
            $sylius['template'] = $this->template;
        }

        if($this->paginate) {
            $sylius['paginate'] = $this->paginate;
        }

        if($this->sorting) {
            $sylius['sorting'] = $this->sorting;
        }

        if(!isset($defaults['_sylius']) && count($sylius)) {
             $defaults['_sylius'] = $sylius;
        }

        return $defaults;
    }
}