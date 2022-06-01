import EntityFilter from "@enhavo/app/grid/filter/model/EntityFilter";
import AbstractFactory from "@enhavo/app/grid/filter/factory/AbstractFactory";

export default class EntityFactory extends AbstractFactory
{
    createFromData(data: object): EntityFilter
    {
        let filter = <EntityFilter>super.createFromData(data);

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

    createNew(): EntityFilter {
        return new EntityFilter();
    }
}