## Making a resource sortable

Sometimes the instances of your resource need to be in a specific order
that can be changed by the user. An example would be the entries of a
menu, or slides in a slider.

For this example, we will use the entity Slide from EnhavoSliderBundle.

### Add position property

The first thing your resource needs to have is an integer property to
save it\'s position in the order. In this example it will be called
`position`.

```php
class Slider {

    //...

    /**
     * @var integer
     **/
    protected position;

    /**
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    //...

}
```

This property also needs to be added to the Doctrine definitions, of
course.

### Generate routes

If you are creating a new resource rather than modifying an existing
one, you can use the route generator command ([see CRUD Routing
generator](/book/routing-bundle/index.md)). It has an optional
parameter `sorting` that, if set to the property name defined above,
adds route configurations for sortable resources.

### Add route changes manually

If you are not creating a new resource but rather modifying an existing
one, here are the route configurations to do manually to activate
sortable behaviour.

#### 1. Add routes for moving items to new positions

There are two routes specifically for moving the resource item to its
new position. These are not present by default, they are only needed for
sortable resources.

Note: If you changed the pagination value in the table route, you need
to add the same value to the move_to_page route as well (defaults:
\_sylius: paginate: x)

```yaml
enhavo_slider_slide_move_after:
    options:
        expose: true
    path: /enhavo/slider/slide/move-after/{id}/{target}
    methods: [POST]
    defaults:
        _controller: {{ app }}.controller.{{ resource }}:moveAfterAction

enhavo_slider_slide_move_to_page:
    options:
        expose: true
    path: /enhavo/slider/slide/move-to-page/{id}/{page}/{top}
    methods: [POST]
    defaults:
        _controller: {{ app }}.controller.{{ resource }}:moveDownAction
```

#### 2. Modify table route

The table route defines the view where the user can see a table of all
the resource items. You need to modify this route so that the items
appear in the right order. Also you have to add an extra column to the
table to display the drag/drop button for moving the item.

```yaml
enhavo_slider_slide_table:
    options:
        expose: true
    path: /enhavo/slider/slide/table
    methods: [GET]
    defaults:
        _controller: enhavo_slider.controller.slide:tableAction
        _sylius:
            sortable: true              # add sortable
            sorting:                    # add sorting
                position: desc          # [property name]:[sort order], can be "desc" or "asc"
            viewer:
                columns:
                    # ... other columns
                    position:                                                  # add this column
                        type: position                                         #
```

Commented lines are new.

#### 3. Modify create route

If a new item of the resource is created, it needs an initial value for
its sorting position. Therefore, you also need to modify the create
route.

```yaml
enhavo_slider_slide_create:
    options:
        expose: true
    path: /enhavo/slider/slide/create
    methods: [GET,POST]
    defaults:
        _controller: enhavo_slider.controller.slide:createAction
        _sylius:
            sortable: true  # add sortable
```

Commented lines are new.

If the value of `initial` is **\"max\"** (default), the newly created
item will have an initial position value that is the current maximum
value plus one. If your sorting order defined in previous routes is
**\"desc\"**, this means that the new item will be the new first
element, else it will be the last. A value of **\"min\"** will set the
initial value to 0 and shift all existing items up by one, which can be
slow for large amounts of data and is not recommended.
