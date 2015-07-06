<?php
/**
 * ItemFormType.php
 *
 * @since 06/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

abstract class ItemFormType extends AbstractType
{
    /**
     * @var string
     */
    private $formName;

    /**
     * @return string
     */
    public function getFormName()
    {
        return $this->formName;
    }

    /**
     * @param string $formName
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($this->getFormName() !== null) {
            $view->vars['full_name'] = sprintf('%s[itemType]', $this->getFormName());
        }
    }
}