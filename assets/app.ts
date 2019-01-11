import { ViewManager } from '@enhavo/app'
import { ViewStack } from '@enhavo/app'
import { ViewSubscriber } from '@enhavo/app'

let stack = new ViewStack();
let manager = new ViewManager(window, stack);
let subscriber = new ViewSubscriber(window, manager);
