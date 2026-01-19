import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {Form} from "@enhavo/vue-form/model/Form";
import {TestForm} from "@enhavo/vue-form/tests/mock/TestForm";

export class TestVisitor implements FormVisitorInterface
{
    constructor(
        public value: string,
        public priority: number = 10
    ) {
    }

    apply(form: TestForm): Form | void
    {
        if (!form.testValues) {
            form.testValues = [];
        }

        form.testValues.push(this.value);
    }

    getPriority(): number
    {
        return this.priority;
    }

    setPriority(priority: number): void
    {

    }

    supports(form: Form): boolean
    {
        return true;
    }
}
