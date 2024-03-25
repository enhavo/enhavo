## Overwrite application service

In some cases you want to add your own logic or replace the default one.
If this logic is in a service that is loaded by the application, you can
just overwrite this service and change it to your needs. Just follow
these steps.

### Create service

Create your own service and extend from the old one

```typescript
// assets/enhavo/lib/MyView.ts

import View from '@enhavo/app/View';

export default class MyView extends View
{
    // ... overwrite or add function
}
```

### Create application

Create your application class and extend from the old. Just replace the
services you want by overwriting the parent methods.

```typescript
// assets/enhavo/lib/MyApplication.ts

import Application from "@enhavo/app/Application";
import MyView from "./MyView"

export default class MyApplication extends Application
{
    public getView()
    {
        if(this.view == null) {
            this.view = new MyView(this.getDataLoader().load()['view']);
            this.view.setEventDispatcher(this.getEventDispatcher());
        }
        return this.view;
    }
}
```

### Use application

Replace your the default application with your own one by changing the
import statement

```typescript
// assets/enhavo/view.ts

import Application from "./lib/MyApplication";
```
