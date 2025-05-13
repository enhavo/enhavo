<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Event;

class ResourceEvents
{
    public const PRE_CREATE = 'enhavo_resource.pre_create';
    public const POST_CREATE = 'enhavo_resource.post_create';
    public const PRE_UPDATE = 'enhavo_resource.pre_update';
    public const POST_UPDATE = 'enhavo_resource.post_update';
    public const PRE_DELETE = 'enhavo_resource.pre_delete';
    public const POST_DELETE = 'enhavo_resource.post_delete';
}
