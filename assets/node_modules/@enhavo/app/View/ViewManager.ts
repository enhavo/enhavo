
import { ViewStack } from "@enhavo/app-assets/View/ViewStack";

export class ViewManager
{
    private window: Window;

    private stack: ViewStack;

    constructor(window: Window, stack: ViewStack)
    {
        this.window = window;
        this.stack = stack;
    }

    public setRootView(view: View)
    {

    }
}