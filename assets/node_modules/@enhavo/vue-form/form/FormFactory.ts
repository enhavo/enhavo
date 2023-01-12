import * as _ from "lodash";
import {Form} from "@enhavo/vue-form/model/Form";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {FormEventDispatcherInterface} from "@enhavo/vue-form/form/FormEventDispatcherInterface";

export class FormFactory
{
    private visitors: Array<FormVisitorInterface> = [];
    private models: Array<ModelEntry> = [];

    constructor(
        private eventDispatcher: FormEventDispatcherInterface
    ) {
    }

    public create(data: object, visitors: FormVisitorInterface|Array<FormVisitorInterface>|Theme = null, parent: Form = null)
    {
        let form = this.getModel(data);

        form = _.extend(form, data);
        form.parent = parent;

        for (let key in form.children) {
            form.children[key] = this.createFormData(form.children[key]);
            form.children[key].parent = form;
        }

        let applyVisitors = [];
        for (let visitor of this.visitors) {
            if (applyVisitors.indexOf(visitor) === -1) {
                applyVisitors.push(visitor);
            }
        }

        if (visitors) {
            if (visitors instanceof Theme) {
                visitors = visitors.getVisitors();
            }

            for (let visitor of this.getVisitors(visitors)) {
                if (applyVisitors.indexOf(visitor) === -1) {
                    applyVisitors.push(visitor);
                }
            }
        }

        applyVisitors = applyVisitors.sort((a: FormVisitorInterface, b: FormVisitorInterface) => {
            return b.getPriority() - a.getPriority();
        })

        form = this.applyVisitors(form, applyVisitors);

        let root = form.getRoot();
        for (let applyVisitor of applyVisitors) {
            if (root.visitors.indexOf(applyVisitor) == -1) {
                root.visitors.push(applyVisitor);
            }
        }

        this.initForm(form);

        return form;
    }

    private initForm(form: Form)
    {
        form.init();
        for (let child of form.children) {
            this.initForm(child);
        }
    }

    private createFormData(form: Form)
    {
        form = _.assign(this.getModel(form), form);
        for (let key in form.children) {
            form.children[key] = this.createFormData(form.children[key]);
            form.children[key].parent = form;
        }
        return form;
    }

    public addVisitor(visitor: FormVisitorInterface)
    {
        this.visitors.push(visitor);
    }

    public addVisitors(visitors: Array<FormVisitorInterface>)
    {
        for (let visitor of visitors) {
            this.addVisitor(visitor);
        }
    }

    public addTheme(theme: Theme)
    {
        this.addVisitors(theme.getVisitors());
    }

    private getVisitors(visitors: FormVisitorInterface|Array<FormVisitorInterface> = null): Array<FormVisitorInterface>
    {
        let applyVisitors = [];

        if (Array.isArray(visitors)) {
            for (let visitor of visitors) {
                applyVisitors.push(visitor);
            }
        } else {
            applyVisitors.push(visitors);
        }

        return applyVisitors;
    }

    private applyVisitors(form: Form, visitors: Array<FormVisitorInterface>): Form
    {
        form = this.updateComponent(form, visitors);
        for (let key in form.children) {
            form.children[key] = this.applyVisitors(form.children[key], visitors);
        }
        return form;
    }

    private updateComponent(form: Form, visitors: Array<FormVisitorInterface>): Form
    {
        let applicableVisitors = [];
        for (let visitor of visitors) {
            if (visitor.supports(form)) {
                applicableVisitors.push(visitor);
            }
        }

        for (let applicableVisitor of applicableVisitors) {
            let returnForm = applicableVisitor.apply(form);
            if (returnForm) {
                form = returnForm;
            }
        }

        return form;
    }

    public registerModel(componentModel: string, prototype: Form)
    {
        let entry = this.getModelEntry(componentModel);
        if (entry) {
            this.models.splice(this.models.indexOf(entry), 1);
        }

        this.models.push(new ModelEntry(componentModel, prototype));
    }

    private getModelEntry(componentModel: string): ModelEntry|null
    {
        for (let entry of this.models) {
            if (entry.componentModel == componentModel) {
                return entry;
            }
        }

        return null;
    }

    private getModel(form: Form|object): any
    {
        if ((<Form>form).componentModel) {
            let entry = this.getModelEntry((<Form>form).componentModel);
            if (entry) {
                let clone = _.clone(entry.model);
                clone.eventDispatcher = this.eventDispatcher;
                return clone;
            }
        }

        let model = new Form();
        model.eventDispatcher = this.eventDispatcher;
        return model;
    }
}

class ModelEntry
{
    constructor(
        public componentModel: string,
        public model: Form
    ) {
    }
}
