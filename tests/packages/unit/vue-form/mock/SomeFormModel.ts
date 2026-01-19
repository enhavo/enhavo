import {Form} from "@enhavo/vue-form/model/Form";

export class SomeFormModel extends Form
{
    public testValue: string = 'something';
    public testInit: boolean = false;

    constructor(
        private service: SomeService
    ) {
        super();
    }

    init() {
        this.testInit = true;
    }
}

export class SomeService
{

}