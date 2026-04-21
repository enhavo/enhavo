<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;
use Symfony\Component\Form\FormInterface;

interface FormDescriberInterface
{
    public function describe(FormInterface $form, Schema $schema): void;
}
