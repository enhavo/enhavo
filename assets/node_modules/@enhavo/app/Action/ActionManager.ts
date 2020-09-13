import ActionInterface from "@enhavo/app/Action/ActionInterface";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";
import * as _ from 'lodash';

export default class ActionManager
{
    public primary: ActionInterface[];
    public secondary: ActionInterface[];
    private readonly registry: ActionRegistry;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(
        primary: ActionInterface[],
        secondary: ActionInterface[],
        registry: ActionRegistry,
        componentRegistry: ComponentRegistryInterface
    ) {
        this.primary = primary;
        this.secondary = secondary;
        this.registry = registry;
        this.componentRegistry = componentRegistry;
    }

    init()
    {
        for (let i in this.primary) {
            let action = this.registry.getFactory(this.primary[i].component).createFromData(this.primary[i]);
            _.extend(this.primary[i], action);
        }

        for (let i in this.secondary) {
            let action = this.registry.getFactory(this.secondary[i].component).createFromData(this.secondary[i]);
            _.extend(this.secondary[i], action);
        }

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('actionManager', this);
        this.componentRegistry.registerData(this.primary);
        this.componentRegistry.registerData(this.secondary);
    }

    hasActions() {
        return (this.primary && this.primary.length > 0) || (this.secondary && this.secondary.length > 0)
    }
}
