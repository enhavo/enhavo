import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";

export default abstract class AbstractWidget implements WidgetInterface
{
    component: string;
    name: string;
}
