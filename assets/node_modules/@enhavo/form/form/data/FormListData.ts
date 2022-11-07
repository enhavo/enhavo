import {FormData} from "@enhavo/vue-form/data/FormData";

export class FormListData extends FormData
{
    public border: boolean;
    public sortable: boolean;
    public allowDelete: boolean;
    public allowAdd: boolean;
    public blockName: string;
    public prototype: FormData;
    public prototypeName: string;
    public index: number;
    public itemComponent: string;
    public onDelete: (form: FormListData) => boolean|Promise<boolean>;
    public onMove: (form: FormListData) => void;
    public draggableGroup: string
    public draggableHandle: string
}
