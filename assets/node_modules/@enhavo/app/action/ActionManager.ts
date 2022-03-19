import ActionInterface from "@enhavo/app/action/ActionInterface";
import ActionRegistry from "@enhavo/app/action/ActionRegistry";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

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
        this.initializeActions(this.primary);
        this.initializeActions(this.secondary);

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('actionManager', this);
        this.primary = this.componentRegistry.registerData(this.primary);
        this.secondary = this.componentRegistry.registerData(this.secondary);
    }

    hasActions() {
        return (this.primary && this.primary.length > 0) || (this.secondary && this.secondary.length > 0)
    }

    initializeActions(actions: ActionInterface[]): void
    {
        for (let i in actions) {
            actions[i] = this.registry.getFactory(actions[i].component).createFromData(actions[i]);
        }
    }
}
