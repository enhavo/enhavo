<?php
/**
 * AdminView.php
 *
 * @since 04/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Model;


use Enhavo\Bundle\AppBundle\Admin\Config;

class AdminView
{
    /**
     * @var string
     */
    private $deleteRoute;

    /**
     * @var string
     */
    private $indexRoute;

    /**
     * @var string
     */
    private $createRoute;

    /**
     * @var string
     */
    private $editRoute;

    /**
     * @var string
     */
    private $tableRoute;

    /**
     * @var string
     */
    private $addButtonText;

    /**
     * @var string
     */
    private $emptyTableText;

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config;
    }

    /**
     * @param string $addButtonText
     */
    public function setAddButtonText($addButtonText)
    {
        $this->addButtonText = $addButtonText;
    }

    /**
     * @return string
     */
    public function getAddButtonText()
    {
        return $this->addButtonText;
    }

    /**
     * @param string $createRoute
     */
    public function setCreateRoute($createRoute)
    {
        $this->createRoute = $createRoute;
    }

    /**
     * @return string
     */
    public function getCreateRoute()
    {
        return $this->createRoute;
    }

    /**
     * @param string $editRoute
     */
    public function setEditRoute($editRoute)
    {
        $this->editRoute = $editRoute;
    }

    /**
     * @return string
     */
    public function getEditRoute()
    {
        return $this->editRoute;
    }

    /**
     * @param string $emptyTableText
     */
    public function setEmptyTableText($emptyTableText)
    {
        $this->emptyTableText = $emptyTableText;
    }

    /**
     * @return string
     */
    public function getEmptyTableText()
    {
        return $this->emptyTableText;
    }

    /**
     * @param string $indexRoute
     */
    public function setIndexRoute($indexRoute)
    {
        $this->indexRoute = $indexRoute;
    }

    /**
     * @return string
     */
    public function getIndexRoute()
    {
        return $this->indexRoute;
    }

    /**
     * @param string $tableRoute
     */
    public function setTableRoute($tableRoute)
    {
        $this->tableRoute = $tableRoute;
    }

    /**
     * @return string
     */
    public function getTableRoute()
    {
        return $this->tableRoute;
    }

    /**
     * @param Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $deleteRoute
     */
    public function setDeleteRoute($deleteRoute)
    {
        $this->deleteRoute = $deleteRoute;
    }

    /**
     * @return string
     */
    public function getDeleteRoute()
    {
        return $this->deleteRoute;
    }

    public function getJavascripts()
    {
        $javascripts = array();
        /** @var $javascript Javascript */
        foreach($this->getConfig()->getJavascripts() as $javascript) {
            $javascripts[] = $javascript->getFile();
        }
        return $javascripts;
    }
} 