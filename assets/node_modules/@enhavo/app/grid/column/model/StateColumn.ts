import AbstractColumn from "@enhavo/app/grid/column/model/AbstractColumn";

export default class StateColumn extends AbstractColumn
{
    property: string;
    sortingProperty: string;
    wrap: boolean;
}