import {Form} from "@enhavo/vue-form/model/Form";

export class TestForm extends Form
{
    constructor(
        public name: string = null,
    ) {
        super();
    }

    public foo = "bar";
    public visitors: string[] = [];
    public testValues: string[];
}
