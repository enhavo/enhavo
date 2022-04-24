import ColumnInterface from "@enhavo/app/grid/column/ColumnInterface";
import ColumnRegistry from "@enhavo/app/grid/column/ColumnRegistry";
import * as _ from 'lodash';
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class ColumnManager
{
    public columns: ColumnInterface[];

    private readonly registry: ColumnRegistry;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(columns: ColumnInterface[], registry: ColumnRegistry, componentRegistry: ComponentRegistryInterface)
    {
        this.componentRegistry = componentRegistry;
        this.registry = registry;
        this.columns = columns;
    }

    init() {
        for (let i in this.columns) {
            this.columns[i] = this.registry.getFactory(this.columns[i].component).createFromData(this.columns[i]);
        }

        for (let component of this.registry.getComponents()) {
            this.componentRegistry.registerComponent(component.name, component.component)
        }

        this.columns = this.componentRegistry.registerData(this.columns);
        this.componentRegistry.registerStore('columnManager', this);
    }

    getSortingParameters()
    {
        let parameters = [];
        for(let column of this.columns) {
            if(column.directionDesc != null) {
                parameters.push({
                   property: column.sortingProperty,
                   direction:  column.directionDesc ? 'desc' : 'asc'
                });
            }
        }
        return parameters;
    }
}
