## Add batch action

::: note
::: title
Note
:::

This article outdated and may contain information that are not in use
anymore
:::

All bundles have the batch action \"delete\" by default. Here we will
add an additional custom batch action by using the page bundles
*publish* action as an example.

### Configure route

First we will have to define the new batch action in the table route.

The section batch_actions already has the delete action defined by
default. If we edit this default value by manually adding it to the
route, the new setting will replace the default actions including
delete. Therefore we also have to add the delete action again, or else
it will disappear.

``` yaml
enhavo_page_page_table:
    ...
    defaults:
        ...
        _viewer:
            ...
            table:
                batch_actions:
                    delete:
                        label: table.batch.action.delete
                        confirm_message: table.batch.message.confirm.delete
                        translation_domain: EnhavoAppBundle
                        permission: ROLE_ENHAVO_PAGE_PAGE_DELETE
                        position: 0
                    publish:                                                        # Canonical name of the action
                        label: page.batch.action.publish                            # Text in the dropdown menu
                        confirm_message: page.batch.message.confirm.publish         # Confirm message that appears in a popup when the action is submitted
                        translation_domain: EnhavoPageBundle                        # Translation domain of label and confirm_message, default: current bundle
                        permission: ROLE_ENHAVO_PAGE_PAGE_EDIT                      # Security permission needed to run this action, default: ~ (for always usable)
                        position: 1                                                 # Lower numbers mean higher position in the dropdown menu, default: ~ (add at the bottom)
                columns:
                    ...
```

**Note**: If a user doesn\'t have the necessary security permission to
run a batch action, it will not be displayed in his batch action
dropdown either. If there\'s no allowed action for the current user,
checkboxes and dropdown menu will not be visible at all.

### Add action

Now we need to add the code that will be run once the batch action has
been submitted.

To do this, we add a function to our resource\'s controller. The name of
the function must be *batchAction* followed by the canonical name of the
action. In our case, the name is *batchActionPublish*.

``` php
public function batchActionPublish($resources)
{
    $this->isGrantedOr403('edit');
    $em = $this->get('doctrine.orm.entity_manager');
    /** @var Page $page */
    foreach ($resources as $page) {
        if (!$page->getPublic()) {
            $page->setPublic(true);
            $em->persist($page);
        }
    }
    $em->flush();

    return true;
}
```

The function will get an unsorted array of the selected resources as
parameter. It should return *false* if an error occurred and *true*
otherwise.

**Note**: You can change the behaviour of the delete batch action by
overriding *batchActionDelete* in your controller.

All done, we now have a working custom batch action.
