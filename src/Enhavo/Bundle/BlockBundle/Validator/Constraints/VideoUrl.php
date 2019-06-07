<?php
namespace Enhavo\Bundle\BlockBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VideoUrl extends Constraint
{
    public $message = 'Please choose a youtube or vimeo video!';

    public function validatedBy()
    {
        return 'video_url';
    }
}