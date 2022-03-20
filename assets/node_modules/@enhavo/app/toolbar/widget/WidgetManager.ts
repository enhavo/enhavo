import WidgetInterface from "@enhavo/app/toolbar/widget/WidgetInterface";
import WidgetRegistry from "@enhavo/app/toolbar/widget/WidgetRegistry";
import * as _ from 'lodash';
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class WidgetManager
{
    public primary: WidgetInterface[];
    public secondary: WidgetInterface[];

    private readonly registry: WidgetRegistry;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(primary: WidgetInterface[], secondary: WidgetInterface[], registry: WidgetRegistry, componentRegistry: ComponentRegistryInterface)
    {
        this.primary = primary;
        this.secondary = secondary;
        this.registry = registry;
        this.componentRegistry = componentRegistry;
    }

    init() {
        for (let i in this.primary) {
            let action = this.registry.getFactory(this.primary[i].component).createFromData(this.primary[i]);
            this.primary[i] = _.extend(this.primary[i], action);
        }

        for (let i in this.secondary) {
            let action = this.registry.getFactory(this.secondary[i].component).createFromData(this.secondary[i]);
            this.secondary[i] = _.extend(this.secondary[i], action);
        }

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.componentRegistry.registerStore('widgetManager', this);
        this.primary = this.componentRegistry.registerData(this.primary);
        this.secondary = this.componentRegistry.registerData(this.secondary);
    }
}
