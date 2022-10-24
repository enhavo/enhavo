import {FormData} from "@enhavo/vue-form/data/FormData";

export class Theme
{
    private visitors: Array<ThemeVisitorInterface> = [];

    addVisitor(visitor: ThemeVisitorInterface)
    {
        this.visitors.push(visitor);
    }

    apply(form: FormData)
    {
        this.updateComponent(form);
        for (let key in form.children) {
            if (form.children.hasOwnProperty(key)) {
                this.apply(form.children[key]);
            }
        }
    }

    merge(theme: Theme)
    {
        for (let visitor of theme.visitors) {
            this.addVisitor(visitor);
        }
    }

    private updateComponent(form: FormData)
    {
        let applicableVisitors = [];
        for (let visitor of this.visitors) {
            if (visitor.supports(form)) {
                applicableVisitors.push(visitor);
            }
        }

        for (let applicableVisitor of applicableVisitors) {
            applicableVisitor.apply(form);
        }
    }
}

export interface ThemeVisitorInterface
{
    supports(form: FormData): boolean;
    apply(form: FormData): void;
    getPriority(): number;
}

export class ThemeVisitor implements ThemeVisitorInterface
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

export class ThemeComponentVisitor implements ThemeVisitorInterface
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
