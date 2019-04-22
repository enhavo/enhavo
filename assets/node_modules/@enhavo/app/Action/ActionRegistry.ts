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
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import DropdownActionComponent from './Components/DropdownActionComponent.vue'
import ActionComponent from './Components/ActionComponent.vue'

export default class ActionRegistry extends Registry
{
    getFactory(name: string): ActionFactoryInterface {
        return <ActionFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ActionFactoryInterface): void {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        this.register('close-action', ActionComponent, new CloseActionFactory(application));
        this.register('create-action', ActionComponent, new CreateActionFactory(application));
        this.register('delete-action', ActionComponent, new DeleteActionFactory(application));
        this.register('dropdown-action', DropdownActionComponent, new DropdownActionFactory(application));
        this.register('filter-action', ActionComponent, new FilterActionFactory(application));
        this.register('preview-action', ActionComponent, new PreviewActionFactory(application));
        this.register('save-action', ActionComponent, new SaveActionFactory(application));
        this.register('event-action', ActionComponent, new EventActionFactory(application));
        this.register('open-action', ActionComponent, new OpenActionFactory(application));
        this.register('duplicate-action', ActionComponent, new DuplicateActionFactory(application));
        this.register('download-action', ActionComponent, new DownloadActionFactory(application));
    }
}
