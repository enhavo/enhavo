import WidgetInterface from "@enhavo/app/toolbar/widget/WidgetInterface";

export default abstract class AbstractWidget implements WidgetInterface
{
    component: string;
    name: string;
}
