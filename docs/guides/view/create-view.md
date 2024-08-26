## Create view

```ts
import View from "@enhavo/app/View/View";
import FrameEventDispatcher from "@enhavo/app/ViewStack/FrameEventDispatcher";
import * as $ from "jquery";

let view = new View();
let eventDispatcher = new FrameEventDispatcher(view);
view.setEventDispatcher(eventDispatcher);
view.addDefaultCloseListener();

$(function() {
    view.ready();
});
```
