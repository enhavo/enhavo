import BooleanFilter from "@enhavo/app/grid/filter/model/BooleanFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

export default class BooleanFactory extends AbstractFactory
{
    createNew(): BooleanFilter {
        return new BooleanFilter();
    }
}