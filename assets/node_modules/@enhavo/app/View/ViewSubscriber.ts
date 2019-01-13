import { ViewManager } from "./ViewManager";

export class ViewSubscriber
{
    private window: Window;

    private manager: ViewManager;

    constructor(window: Window, manager: ViewManager)
    {
        this.window = window;
        this.manager = manager;
    }
}