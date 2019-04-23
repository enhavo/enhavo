import { Registry } from "@enhavo/core";
import FilterFactoryInterface from "@enhavo/app/Grid/Filter/FilterFactoryInterface";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import FilterAutoCompleteComponent from "@enhavo/app/Grid/Filter/Components/FilterAutoCompleteComponent.vue";
import FilterCheckboxComponent from "@enhavo/app/Grid/Filter/Components/FilterCheckboxComponent.vue";
import FilterDropdownComponent from "@enhavo/app/Grid/Filter/Components/FilterDropdownComponent.vue";
import FilterTextComponent from "@enhavo/app/Grid/Filter/Components/FilterTextComponent.vue";
import AutoCompleteEntityFactory from "@enhavo/app/Grid/Filter/Factory/AutoCompleteEntityFactory";
import BooleanFactory from "@enhavo/app/Grid/Filter/Factory/BooleanFactory";
import EntityFactory from "@enhavo/app/Grid/Filter/Factory/EntityFactory";
import OptionFactory from "@enhavo/app/Grid/Filter/Factory/OptionFactory";
import TextFactory from "@enhavo/app/Grid/Filter/Factory/TextFactory";

export default class FilterRegistry extends Registry
{
    getFactory(name: string): FilterFactoryInterface {
        return <FilterFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: FilterFactoryInterface): void {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        this.register('filter-autocomplete-entity', FilterAutoCompleteComponent, new AutoCompleteEntityFactory(application));
        this.register('filter-boolean', FilterCheckboxComponent, new BooleanFactory(application));
        this.register('filter-entity', FilterDropdownComponent, new EntityFactory(application));
        this.register('filter-option', FilterDropdownComponent, new OptionFactory(application));
        this.register('filter-text', FilterTextComponent, new TextFactory(application));
    }
}
