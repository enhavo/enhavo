<?php
/**
 * ResourceEvents.php
 *
 * @since 29/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Event;

class ResourceEvents
{
    const PRE_CREATE = 'enhavo_app.pre_create';
    const POST_CREATE = 'enhavo_app.post_create';
    const PRE_UPDATE = 'enhavo_app.pre_update';
    const POST_UPDATE = 'enhavo_app.post_update';
    const PRE_DELETE = 'enhavo_app.pre_delete';
    const POST_DELETE = 'enhavo_app.post_delete';
}
