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

class ResetPasswordViewer extends AbstractViewer
{
    public function getType()
    {
        return 'reset_password';
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