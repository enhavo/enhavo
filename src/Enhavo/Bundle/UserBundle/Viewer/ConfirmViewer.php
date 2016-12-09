<?php
/**
 * ResetPasswordViewer.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;
use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;

class ConfirmViewer extends AbstractViewer
{
    public function getType()
    {
        return 'confirm';
    }

    public function getMailTemplate()
    {
        return $this->optionAccessor->get('mailTemplate');
    }

    public function getConfirmRoute()
    {
        return $this->optionAccessor->get('confirmRoute');
    }

    public function getRedirectRoute()
    {
        return $this->optionAccessor->get('confirmRoute');
    }

    public function configureOptions(OptionAccessor $optionsAccessor)
    {
        parent::configureOptions($optionsAccessor);
        $optionsAccessor->setDefaults([
            'mailTemplate' => '',
        ]);
    }
}