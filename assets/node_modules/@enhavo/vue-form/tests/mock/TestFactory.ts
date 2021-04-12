import {FormFactoryInterface} from "@enhavo/vue-form/form/FormFactoryInterface";
import {Form} from "@enhavo/vue-form/form/Form";
import {TestForm} from "@enhavo/vue-form/tests/mock/TestForm";

export class TestFactory implements FormFactoryInterface
{
    createForm(data: object): Form
    {
        return new TestForm();
    }
}
