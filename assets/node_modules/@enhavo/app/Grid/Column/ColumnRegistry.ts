import { Registry } from "@enhavo/core";
import ColumnFactoryInterface from "@enhavo/app/Grid/Column/ColumnFactoryInterface";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import TextFactory from "@enhavo/app/Grid/Column/Factory/TextFactory";
import ColumnTextComponent from "@enhavo/app/Grid/Column/Components/ColumnTextComponent.vue";

export default class ColumnRegistry extends Registry
{
    getFactory(name: string): ColumnFactoryInterface {
        return <ColumnFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ColumnFactoryInterface): void {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        // this.register('column-boolean', FilterAutoCompleteComponent, new AutoCompleteEntityFactory(application));
        // this.register('column-date', FilterCheckboxComponent, new BooleanFactory(application));
        // this.register('column-date-time', FilterDropdownComponent, new EntityFactory(application));
        // this.register('column-label', FilterDropdownComponent, new OptionFactory(application));
        // this.register('column-list', FilterTextComponent, new TextFactory(application));
        // this.register('column-multiple-property', FilterTextComponent, new TextFactory(application));
        // this.register('column-position', FilterTextComponent, new TextFactory(application));
        // this.register('column-property', FilterTextComponent, new TextFactory(application));
        // this.register('column-template', FilterTextComponent, new TextFactory(application));
        this.register('column-text', ColumnTextComponent, new TextFactory(application));
        // this.register('column-url', FilterTextComponent, new TextFactory(application));
    }
}
