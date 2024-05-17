## Menu

The main menu in the admin backend is configurable via the parameter
`enhavo_admin.menu` in the configuration file app/config/enhavo.yml. The
logout button will be added automatically.

``` yaml
enhavo_admin:
    menu:
        user:
            type: base
            label: label.user
            route: enhavo_user_user_index
            role: ROLE_ESPERANTO_USER_USER_INDEX
        group:
            type: base
            label: label.group
            route: enhavo_user_group_index
            role: ROLE_ESPERANTO_USER_GROUP_INDEX
        my_resource:
            type: base
            label: My Resource
            route: acme_my_resource_index
            role: ROLE_MY_RESOURCE_INDEX
```

### Hook

There is an event called `enhavo_app.menu` that you can hook into to
modify the menu before it will be rendered.

Note that by default there already is a listener hooked to this event,
which is responsible for the logout button, permissions and styles. If
you want to make sure that your listener is called after this, you need
your priority to be below 0.

``` php
namespace Acme\FooBundle\EventListener;

use Enhavo\Bundle\AppBundle\Menu\MenuEvent;

class MenuEventListener
{
    public function onMenu(MenuEvent $event)
    {
        $menu = $event->getMenu();
        foreach($menu->getChildren() as $child) {
            $menu->setAttribute('class', 'menu');
        }
    }
}
```

``` yaml
acme_foo.menu_event_listener:
    class: Acme\FooBundle\EventListener\MenuEventListener
    tags:
      - { name: kernel.event_listener, event: enhavo_app.menu, method: onMenu, priority: -1 }
```
