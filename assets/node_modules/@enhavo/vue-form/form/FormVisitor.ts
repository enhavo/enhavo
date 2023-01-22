import {Form} from "@enhavo/vue-form/model/Form";

export interface FormVisitorInterface
{
    supports(form: Form): boolean;
    apply(form: Form): Form|void;
    getPriority(): number;
    setPriority(priority: number): void;
}

export abstract class AbstractFormVisitor implements FormVisitorInterface
{
    protected priority: number = 100;

    abstract supports(form: Form): boolean;
    abstract apply(form: Form): Form|void;

    getPriority(): number
    {
        return this.priority;
    }

    setPriority(priority: number): void
    {
        this.priority = priority;
    }
}

export class FormVisitor implements FormVisitorInterface
{
    constructor(
        private supportCallback: (form: Form) => boolean = null,
        private applyCallback: (form: Form) => Form|void = null,
        private priority: number = 100,
    ) {
    }

    supports(form: Form): boolean
    {
        return this.supportCallback(form);
    }

    setSupportCallback(supportCallback: (form: Form) => boolean): void
    {
        this.supportCallback = supportCallback;
    }

    apply(form: Form): Form|void
    {
        return this.applyCallback(form);
    }

    setApplyCallback(applyCallback: (form: Form) => Form): void
    {
        this.applyCallback = applyCallback;
    }

    getPriority(): number
    {
        return this.priority;
    }

    setPriority(priority: number): void
    {
        this.priority = priority;
    }
}

export class FormComponentVisitor implements FormVisitorInterface
{
    constructor(
        private fromComponent: string|Array<string>,
        private toComponent: string,
        private priority: number = 100,
    ) {
    }

    supports(form: Form): boolean
    {
        if (typeof this.fromComponent === 'string') {
            return form.component == this.fromComponent;
        } else if (Array.isArray(this.fromComponent)) {
            for (let fromComponent of this.fromComponent) {
                if (form.component == fromComponent) {
                    return true;
                }
            }
        }

        return false;
    }

    apply(form: Form): Form|void
    {
        form.component = this.toComponent;
    }

    getPriority(): number
    {
        return this.priority;
    }

    setPriority(priority: number): void
    {
        this.priority = priority;
    }
}
