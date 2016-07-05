<?php
/**
 * ViewerFactory.php
 *
 * @since 28/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

interface RequestConfigurationInterface
{
    public function getViewerOptions();

    public function getViewerType();

    public function getTemplate($default);

    public function isAjaxRequest();

    public function getSortingStrategy();

    public function getBatchType();
}