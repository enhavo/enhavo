import OptionFilter from "@enhavo/app/Grid/Filter/Model/OptionFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class OptionFactory extends AbstractFactory
{
    createFromData(data: object): OptionFilter
    {
        let filter = this.createNew();
        let object = <OptionFilter>data;
        filter.component = object.component;
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