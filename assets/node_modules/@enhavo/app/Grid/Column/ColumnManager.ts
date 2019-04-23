import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";
import ColumnRegistry from "@enhavo/app/Grid/Column/ColumnRegistry";
import * as _ from 'lodash';

export default class ColumnManager
{
    private columns: ColumnInterface[];
    private registry: ColumnRegistry;

    constructor(columns: ColumnInterface[], registry: ColumnRegistry)
    {
        this.registry = registry;
        for (let i in columns) {
            let filter = registry.getFactory(columns[i].component).createFromData(columns[i]);
            _.extend(columns[i], filter);
        }
        this.columns = columns;
    }

    getSortingParameters()
    {
        let parameters = [];
        for(let column of this.columns) {
            if(column.directionDesc != null) {
                parameters.push({
                   property: column.property,
                   direction:  column.directionDesc ? 'desc' : 'asc'
                });
            }
        }
        return parameters;
    }
}