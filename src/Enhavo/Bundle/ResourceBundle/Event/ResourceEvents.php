<?php
/**
 * ResourceEvents.php
 *
 * @since 29/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Event;

class ResourceEvents
{
    const PRE_CREATE = 'enhavo_resource.pre_create';
    const POST_CREATE = 'enhavo_resource.post_create';
    const PRE_UPDATE = 'enhavo_resource.pre_update';
    const POST_UPDATE = 'enhavo_resource.post_update';
    const PRE_DELETE = 'enhavo_resource.pre_delete';
    const POST_DELETE = 'enhavo_resource.post_delete';
}
