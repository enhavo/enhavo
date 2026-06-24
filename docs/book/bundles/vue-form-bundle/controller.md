## Controller

You can use the Stimulus `FormController` provided by `@enhavo/app`. Instead of manually setting up a Vue app,
fetching form data and wiring up the `FormFactory`, you can simply attach the controller to an
HTML element via `data-controller="form"` and pass the form data as a Stimulus value.

When you attach the controller, you must provide a `component` value that tells the
controller which Vue component to render. Use `form-form` to render the complete form.

```html
<div data-controller="form"
     data-form-form-value="{{ form|json_encode|e }}"
     data-form-component-value="form-form">
</div>
```

The controller will mount a Vue app on this element and render the form component with the
reactive form data.

### Inline vue

Inside the controller you can use vue code to render single form elements

```html
<form data-controller="form"
      data-form-form-value="{{ vue|json_encode|e }}">
    <form-row :form="form.get('name')"></form-row>
    <form-widget :form="form.get('_token')"></form-widget>
</form>
```

### Stimulus values

The `FormController` accepts two Stimulus values:

| Value       | Type     | Required | Description                                                 |
|-------------|----------|----------|-------------------------------------------------------------|
| `form`      | `Object` | yes      | The normalized form data created by `VueForm::createData()` |
| `component` | `String` | no       | The Vue component to render.                                |        

