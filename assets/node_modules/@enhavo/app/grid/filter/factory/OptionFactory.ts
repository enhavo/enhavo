import OptionFilter from "@enhavo/app/grid/filter/model/OptionFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

export default class OptionFactory extends AbstractFactory
{
    createFromData(data: object): OptionFilter
    {
        let filter = <OptionFilter>super.createFromData(data);

        if (data.value !== null && data.hasOwnProperty('choices')) {
            for(let choice: object of data.choices) {
                if (choice.hasOwnProperty('code') && choice.code == data.value) {
                    filter.selected = choice;
                    break;
                }
            }
        }
        return filter;
    }

    createNew(): OptionFilter {
        return new OptionFilter();
    }
}