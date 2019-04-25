import { Registry } from "@enhavo/core";
import FilterFactoryInterface from "@enhavo/app/Grid/Filter/FilterFactoryInterface";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import AutoCompleteEntityFactory from "@enhavo/app/Grid/Filter/Factory/AutoCompleteEntityFactory";
import BooleanFactory from "@enhavo/app/Grid/Filter/Factory/BooleanFactory";
import EntityFactory from "@enhavo/app/Grid/Filter/Factory/EntityFactory";
import OptionFactory from "@enhavo/app/Grid/Filter/Factory/OptionFactory";
import TextFactory from "@enhavo/app/Grid/Filter/Factory/TextFactory";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class FilterRegistry extends Registry
{
    getFactory(name: string): FilterFactoryInterface {
        return <FilterFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: FilterFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        this.register('filter-autocomplete-entity', () => import("@enhavo/app/Grid/Filter/Components/FilterAutoCompleteComponent.vue"), new AutoCompleteEntityFactory(application));
        this.register('filter-boolean', () => import("@enhavo/app/Grid/Filter/Components/FilterCheckboxComponent.vue"), new BooleanFactory(application));
        this.register('filter-entity', () => import("@enhavo/app/Grid/Filter/Components/FilterDropdownComponent.vue"), new EntityFactory(application));
        this.register('filter-option', () => import("@enhavo/app/Grid/Filter/Components/FilterDropdownComponent.vue"), new OptionFactory(application));
        this.register('filter-text', () => import("@enhavo/app/Grid/Filter/Components/FilterTextComponent.vue"), new TextFactory(application));
    }
}
