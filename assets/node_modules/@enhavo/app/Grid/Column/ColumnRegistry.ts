import { Registry } from "@enhavo/core";
import ColumnFactoryInterface from "@enhavo/app/Grid/Column/ColumnFactoryInterface";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import BooleanFactory from "@enhavo/app/Grid/Column/Factory/BooleanFactory";
import TextFactory from "@enhavo/app/Grid/Column/Factory/TextFactory";
import ActionFactory from "@enhavo/app/Grid/Column/Factory/ActionFactory";
import SubFactory from "@enhavo/app/Grid/Column/Factory/SubFactory";
import MediaFactory from "@enhavo/app/Grid/Column/Factory/MediaFactory";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class ColumnRegistry extends Registry
{
    getFactory(name: string): ColumnFactoryInterface {
        return <ColumnFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ColumnFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        this.register('column-boolean', () => import("@enhavo/app/Grid/Column/Components/ColumnBooleanComponent.vue"), new BooleanFactory(application));
        this.register('column-text', () => import("@enhavo/app/Grid/Column/Components/ColumnTextComponent.vue"), new TextFactory(application));
        this.register('column-action', () => import("@enhavo/app/Grid/Column/Components/ColumnActionComponent.vue"), new ActionFactory(application));
        this.register('column-sub', () => import("@enhavo/app/Grid/Column/Components/ColumnSubComponent.vue"), new SubFactory(application));
        this.register('column-media', () => import("@enhavo/app/Grid/Column/Components/ColumnMediaComponent.vue"), new MediaFactory(application));
    }
}
