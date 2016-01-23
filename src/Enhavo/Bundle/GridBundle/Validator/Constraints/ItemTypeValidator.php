<?php
namespace Enhavo\Bundle\GridBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\GridBundle\Entity\Video;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ItemTypeValidator extends ConstraintValidator
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function validate($value, Constraint $constraint)
    {
        if($value instanceof Item) {
            if($value->getItemType() instanceof Video) {
                $validator = $this->container->get('validator.unique.video_url_validator');
                $validator->initialize($this->context);
                $validator->validate($value->getItemType(), $constraint);
            }
        }
    }
}