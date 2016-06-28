<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexViewer extends AppViewer
{
    public function getType()
    {
        return 'index';
    }

    public function getTableRoute()
    {
        return sprintf(
            '%s_%s_table',
            $this->metadata->getApplicationName(),
            $this->metadata->getHumanizedName()
        );
    }

    public function getUpdateRoute()
    {
        return sprintf(
            '%s_%s_update',
            $this->metadata->getApplicationName(),
            $this->metadata->getHumanizedName()
        );
    }

    public function getCreateRoute()
    {
        return sprintf(
            '%s_%s_create',
            $this->metadata->getApplicationName(),
            $this->metadata->getHumanizedName()
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'blocks' => [
                'table' => [
                    'type' => 'table',
                    'table_route' => $this->getTableRoute(),
                    'update_route' => $this->getUpdateRoute(),
                ]
            ],
            'actions' => [
                'create' => [
                    'type' => 'create',
                    'route' => $this->getCreateRoute(),
                ]
            ]
        ]);
    }
}