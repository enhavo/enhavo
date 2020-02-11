import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";

export default abstract class AbstractWidget implements WidgetInterface
{
    protected application: ApplicationInterface;
    component: string;
    name: string;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }
}