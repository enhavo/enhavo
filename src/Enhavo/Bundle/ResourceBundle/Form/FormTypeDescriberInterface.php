<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;
use Symfony\Component\Form\FormTypeInterface;

interface FormTypeDescriberInterface extends FormTypeDescribeAwareInterface
{
    public function describe($options, FormTypeInterface $form, Schema $schema);

    public static function getFormTypes(): array;
}
