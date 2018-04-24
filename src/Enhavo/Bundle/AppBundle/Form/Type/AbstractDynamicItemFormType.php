<?php
/**
 * ItemFormType.php
 *
 * @since 06/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

abstract class AbstractDynamicItemFormType extends AbstractType
{
    /**
     * @var string
     */
    protected $translation;

    /**
     * @var string
     */
    private $formName;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

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