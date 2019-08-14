import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import BooleanFactory from "@enhavo/app/Grid/Filter/Factory/BooleanFactory";
import TextFactory from "@enhavo/app/Grid/Filter/Factory/TextFactory";
import AutoCompleteEntityFactory from "@enhavo/app/Grid/Filter/Factory/AutoCompleteEntityFactory";
import EntityFactory from "@enhavo/app/Grid/Filter/Factory/EntityFactory";
import OptionFactory from "@enhavo/app/Grid/Filter/Factory/OptionFactory";

export default class FilterRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('filter-autocomplete-entity', () => import("@enhavo/app/Grid/Filter/Components/FilterAutoCompleteComponent.vue"), new AutoCompleteEntityFactory(application));
        this.register('filter-boolean', () => import("@enhavo/app/Grid/Filter/Components/FilterCheckboxComponent.vue"), new BooleanFactory(application));
        this.register('filter-entity', () => import("@enhavo/app/Grid/Filter/Components/FilterDropdownComponent.vue"), new EntityFactory(application));
        this.register('filter-option', () => import("@enhavo/app/Grid/Filter/Components/FilterDropdownComponent.vue"), new OptionFactory(application));
        this.register('filter-text', () => import("@enhavo/app/Grid/Filter/Components/FilterTextComponent.vue"), new TextFactory(application));
    }
}