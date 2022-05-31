import BetweenFilter from "@enhavo/app/grid/filter/model/BetweenFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

export default class BetweenFactory extends AbstractFactory
{
    createNew(): BetweenFilter {
        return new BetweenFilter();
    }
}
