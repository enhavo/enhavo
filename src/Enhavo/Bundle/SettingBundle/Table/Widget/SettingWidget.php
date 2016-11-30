<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 05/09/16
 * Time: 12:41
 */

namespace Enhavo\Bundle\SettingBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;
use Enhavo\Bundle\MediaBundle\Table\Widget\PictureWidget;
use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingWidget extends AbstractTableWidget
{
    /**
     * @param array $options
     * @param Setting $item
     *
     * @return mixed
     */
    public function render($options, $item)
    {
        if ($item->getType() === Setting::SETTING_TYPE_TEXT) {
            return $item->getValue();
        }

        if ($item->getType() === Setting::SETTING_TYPE_BOOLEAN) {
            $booleanWidget = $this->container->get('enhavo_app.table.boolean');
            $options['property'] = 'value';
            return $booleanWidget->render($options, $item);
        }

        if ($item->getType() === Setting::SETTING_TYPE_FILE) {
            $pictureWidget = $this->container->get('enhavo_media.table.picture_widget');
            $options['property'] = 'file';
            return $pictureWidget->render($options, $item);
        }

        if ($item->getType() === Setting::SETTING_TYPE_FILES) {
            $pictureWidget = $this->container->get('enhavo_media.table.picture_widget');
            $options['property'] = 'files';
            return $pictureWidget->render($options, $item);
        }

        if ($item->getType() === Setting::SETTING_TYPE_WYSIWYG) {
            return $item->getValue();
        }

        if ($item->getType() === Setting::SETTING_TYPE_DATE) {
            $date = $item->getDate();
            if($date instanceof \DateTime) {
                return $date->format('d.m.y');
            }
            return '';
        }

        if ($item->getType() === Setting::SETTING_TYPE_DATETIME) {
            $date = $item->getDate();
            if($date instanceof \DateTime) {
                return $date->format('d.m.y H:i');
            }
            return '';
        }
        
        return '';
    }

    public function getType()
    {
        return 'setting';
    }
}