import DropdownWidget from "@enhavo/app/Toolbar/Widget/Model/DropdownWidget";
import AbstractFactory from "@enhavo/app/Toolbar/Widget/Factory/AbstractFactory";

export default class DropdownWidgetFactory extends AbstractFactory
{
    createNew(): DropdownWidget {
        return new DropdownWidget(this.application)
    }
}