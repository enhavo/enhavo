## Table Route

# Route

Here is a full configuration example of a table route

``` yaml
enhavo_app_table:
    path: /admin/enhavo/app/resource/index
    methods: [GET]
        defaults:
            _controller: app.controller.user:createAction
            _sylius:
                template: App:Backend/User:show.html.twig
                criteria:
                    username: $username
                    enabled:  true
                repository:
                    method: findOneWithFriends
                    arguments: [$username]
                sortable: true
                sorting:
                    score: desc
                paginate: 5
                limit: 3
                viewer
                    type: table
                    parameters:
                        param1: value1
                        param2: value2
                    columns:
                        width: 8
                        columns:
                            id:
                                label: id
                                property: id
                                width: 1
                            title:
                                label: title
                                property: title
                                width: 9
                            public:
                                label: public
                                property: public
                                width: 1
                                widget: EnhavoAppBundle:Widget:boolean.html.twig
                            position:
                                label:
                                property: position
                                width: 1
                                widget:
                                    type: template
                                    template: EnhavoAppBundle:Widget:position.html.twig
                        sorting:
                            sortable: true
                            move_after_route: enhavo_user_user_move_after
                            move_to_page_route: enhavo_user_user_move_to_page
                        batch_actions:
                            delete:
                                label: table.batch.action.delete
                                confirm_message: table.batch.message.confirm.delete
                                translation_domain: EnhavoAppBundle
                                permission: ROLE_ENHAVO_APP_USER_DELETE
                                position: 0
```

# Viewer

  ---------------- ----------------------------------------------------------
  **parameters**   List of parameters passed to the twig template

  **table**        Configuration for the specific table
  ---------------- ----------------------------------------------------------

# Table

``` yaml
table:
    width: 5
    columns:
        id:
            label: id
            property: id
            width: 1
        title:
            label: title
            property: title
            width: 10
        public:
            label: public
            property: public
            widget: EnhavoAdminBundle:Widget:boolean.html.twig
```

  -------------- ----------------------------------------------------------
  **label**      The header of this column

  **property**   Property of that model or row, which should be used to
                 display

  **widget**     A template file that renders that table cell

  **width**      Defines the width of the column
  -------------- ----------------------------------------------------------

# Width

You can define a width for the the table itself and per column. How wide
it is in the end is up to both these variables. The default table
template uses the bootstrap grid for responsive design, mapping the
available page width to 12 columns. If you want your table to stretch
over half of the available area, you have to set `table.width` to 6. The
default value is 12, stretching over the whole area.

The table itself is also divided into 12 columns, regardless of the
value of `table.width`. By setting `table.columns.[column].width`, you
can set the width of each column inside the table. The total sum should
not exceed 12. The default value is 1.

# Widget

A Widget helps you to display a table cell to your specific needs.

Here is an example for how a widget file can look like. The value of the
property will be passed to the widget file as a twig variable called
`value`. Then you can define how it should be rendered.

``` twig
{# EnhavoAdminBundle:Widget:date.html.twig #}
{% if value %}
    {{ value.format('d.m.Y') }}
{% endif %}
```
