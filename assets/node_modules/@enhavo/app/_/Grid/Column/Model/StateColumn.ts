import AbstractColumn from "@enhavo/app/Grid/Column/Model/AbstractColumn";

export default class StateColumn extends AbstractColumn
{
    property: string;
    sortingProperty: string;
    wrap: boolean;
}