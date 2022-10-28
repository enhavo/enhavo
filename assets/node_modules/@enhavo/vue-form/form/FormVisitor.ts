import {FormData} from "@enhavo/vue-form/data/FormData";

export interface FormVisitorInterface
{
    supports(form: FormData): boolean;
    apply(form: FormData): void;
    getPriority(): number;
    setPriority(priority: number): void;
}

export class FormVisitor implements FormVisitorInterface
{
    constructor(
        private supportCallback: (form: FormData) => boolean = null,
        private applyCallback: (form: FormData) => void = null,
        private priority: number = 100,
    ) {
    }

    supports(form: FormData): boolean
    {
        return this.supportCallback(form);
    }

    setSupportCallback(supportCallback: (form: FormData) => boolean): void
    {
        this.supportCallback = supportCallback;
    }

    apply(form: FormData): void
    {
        return this.applyCallback(form);
    }

    setApplyCallback(applyCallback: (form: FormData) => void): void
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

    supports(form: FormData): boolean
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

    apply(form: FormData): void
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