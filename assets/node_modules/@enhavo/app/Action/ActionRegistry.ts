import { Registry } from "@enhavo/core";
import ActionFactoryInterface from "./ActionFactoryInterface";
import CloseActionFactory from "@enhavo/app/Action/Factory/CloseActionFactory";
import CreateActionFactory from "@enhavo/app/Action/Factory/CreateActionFactory";
import DeleteActionFactory from "@enhavo/app/Action/Factory/DeleteActionFactory";
import DropdownActionFactory from "@enhavo/app/Action/Factory/DropdownActionFactory";
import FilterActionFactory from "@enhavo/app/Action/Factory/FilterActionFactory";
import PreviewActionFactory from "@enhavo/app/Action/Factory/PreviewActionFactory";
import SaveActionFactory from "@enhavo/app/Action/Factory/SaveActionFactory";
import EventActionFactory from "@enhavo/app/Action/Factory/EventActionFactory";
import OpenActionFactory from "@enhavo/app/Action/Factory/OpenActionFactory";
import DuplicateActionFactory from "@enhavo/app/Action/Factory/DuplicateActionFactory";
import DownloadActionFactory from "@enhavo/app/Action/Factory/DownloadActionFactory";
import ModalActionFactory from "@enhavo/app/Action/Factory/ModalActionFactory";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class ActionRegistry extends Registry
{
    getFactory(name: string): ActionFactoryInterface {
        return <ActionFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ActionFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        this.register('close-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new CloseActionFactory(application));
        this.register('create-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new CreateActionFactory(application));
        this.register('delete-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new DeleteActionFactory(application));
        this.register('dropdown-action', () => import('@enhavo/app/Action/Components/DropdownActionComponent.vue'), new DropdownActionFactory(application));
        this.register('filter-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new FilterActionFactory(application));
        this.register('preview-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new PreviewActionFactory(application));
        this.register('save-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new SaveActionFactory(application));
        this.register('event-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new EventActionFactory(application));
        this.register('open-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new OpenActionFactory(application));
        this.register('duplicate-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new DuplicateActionFactory(application));
        this.register('download-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new DownloadActionFactory(application));
        this.register('modal-action', () => import('@enhavo/app/Action/Components/ActionComponent.vue'), new ModalActionFactory(application));
    }
}
