## Create view

```ts
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import * as $ from "jquery";

let view = new View();
let eventDispatcher = new EventDispatcher(view);
view.setEventDispatcher(eventDispatcher);
view.addDefaultCloseListener();

$(function() {
    view.ready();
});
```
