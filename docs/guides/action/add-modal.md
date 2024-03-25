## Add Modal

```yaml
actions:
    assign:
        type: modal
        modal:
            type: ajax-form-modal
            route: assign_modal
```

```yaml
assign_modal:
    _controller: enhavo_app.controller.modal:formAction
    _defaults:
        form: App\Form\AssignType
```

```php
namespace App\Form;

class AssignType
{
    public function buildForm(FormBuilder $builder) {
        $builder->add();
    }
}
```