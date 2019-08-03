import EntityFilter from "@enhavo/app/Grid/Filter/Model/EntityFilter";
import AbstractFactory from "@enhavo/app/Grid/Filter/Factory/AbstractFactory";

export default class EntityFactory extends AbstractFactory
{
    createFromData(data: object): EntityFilter
    {
        let action = this.createNew();
        let object = <EntityFilter>data;
        action.component = object.component;
        return action;
    }

    createNew(): EntityFilter {
        return new EntityFilter(this.application)
    }
}