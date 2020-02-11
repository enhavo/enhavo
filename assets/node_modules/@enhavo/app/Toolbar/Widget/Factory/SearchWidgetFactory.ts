import SearchWidget from "@enhavo/app/Toolbar/Widget/Model/SearchWidget";
import AbstractFactory from "@enhavo/app/Toolbar/Widget/Factory/AbstractFactory";

export default class SearchWidgetFactory extends AbstractFactory
{
    createNew(): SearchWidget {
        return new SearchWidget(this.application)
    }
}