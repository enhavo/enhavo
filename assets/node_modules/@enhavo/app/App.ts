import { ViewManager } from '@enhavo/app-assets'
import { ViewStack } from '@enhavo/app-assets'
import { ViewSubscriber } from '@enhavo/app-assets'

let stack = new ViewStack();
let manager = new ViewManager(window, stack);
let subscriber = new ViewSubscriber(window, manager);
