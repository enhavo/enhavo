import IconWidget from "@enhavo/app/Toolbar/Widget/Model/IconWidget";
import AbstractFactory from "@enhavo/app/Toolbar/Widget/Factory/AbstractFactory";

export default class IconWidgetFactory extends AbstractFactory
{
    createNew(): IconWidget {
        return new IconWidget(this.application)
    }
}