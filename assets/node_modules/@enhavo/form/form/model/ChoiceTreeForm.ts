import {ChoiceForm} from "@enhavo/vue-form/model/ChoiceForm"

export class ChoiceTreeForm extends ChoiceForm
{
    public tree: ChoiceRow[];
}

export class ChoiceRow
{
    name: string;
    children: ChoiceRow[];
}
