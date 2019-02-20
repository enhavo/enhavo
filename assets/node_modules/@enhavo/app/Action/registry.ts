import ActionRegistry from './ActionRegistry'
import ActionComponent from './Components/ActionComponent.vue'
import CloseActionFactory from './Factory/CloseActionFactory'
import CreateActionFactory from './Factory/CreateActionFactory'
import DeleteActionFactory from './Factory/DeleteActionFactory'
import DropdownActionFactory from './Factory/DropdownActionFactory'
import DropdownActionComponent from './Components/DropdownActionComponent.vue'
import FilterActionFactory from './Factory/FilterActionFactory'
import PreviewActionFactory from './Factory/PreviewActionFactory'
import SaveActionFactory from './Factory/SaveActionFactory'

let registry = new ActionRegistry();

registry.register('close-action', ActionComponent, new CloseActionFactory());
registry.register('create-action', ActionComponent, new CreateActionFactory());
registry.register('delete-action', ActionComponent, new DeleteActionFactory());
registry.register('dropdown-action', DropdownActionComponent, new DropdownActionFactory());
registry.register('filter-action', ActionComponent, new FilterActionFactory());
registry.register('preview-action', ActionComponent, new PreviewActionFactory());
registry.register('save-action', ActionComponent, new SaveActionFactory());

export default registry;