interface Window
{
    getParent(): Window;
    setParent(window: Window);
}

abstract class AbstractWindow
{
    setOverlay(name: string, window: Window);
    removeWindow(name: string);
    showWindow(name: string);
    hideWindow(name: string);
}

class Navigation implements Window
{
    static readonly navigation = 'navigation';
    static readonly content = 'content';
}