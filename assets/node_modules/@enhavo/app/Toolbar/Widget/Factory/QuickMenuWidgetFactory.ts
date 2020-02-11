import QuickMenuWidget from "@enhavo/app/Toolbar/Widget/Model/QuickMenuWidget";
import AbstractFactory from "@enhavo/app/Toolbar/Widget/Factory/AbstractFactory";

export default class SearchWidgetFactory extends AbstractFactory
{
    createNew(): QuickMenuWidget {
        return new QuickMenuWidget(this.application)
    }
}