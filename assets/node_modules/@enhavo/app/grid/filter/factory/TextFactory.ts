import TextFilter from "@enhavo/app/grid/filter/model/TextFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

export default class TextFactory extends AbstractFactory
{
    createNew(): TextFilter {
        return new TextFilter();
    }
}