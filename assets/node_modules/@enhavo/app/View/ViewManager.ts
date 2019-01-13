import { ViewStack } from "./ViewStack";
import { View } from "./View";

export class ViewManager
{
    private window: Window;

    private stack: ViewStack;

    private root: View|null = null;

    constructor(window: Window, stack: ViewStack)
    {
        this.window = window;
        this.stack = stack;
    }

    public setRootView(view: View)
    {
        this.root = view;
    }

    public getRootView(): View|null
    {
        return this.root;
    }
}