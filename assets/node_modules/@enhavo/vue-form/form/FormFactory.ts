import * as _ from "lodash";
import {Form} from "@enhavo/vue-form/model/Form";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {RootForm} from "@enhavo/vue-form/model/RootForm";

export class FormFactory
{
    private visitors: Array<FormVisitorInterface> = [];

    public create(data: object, visitors: FormVisitorInterface|Array<FormVisitorInterface>|Theme = null, parent: Form = null)
    {
        let form = parent === null ? new RootForm() : new Form();

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

        let root =  <RootForm>form.getRoot();
        for (let applyVisitor of applyVisitors) {
            if (root.visitors.indexOf(applyVisitor) == -1) {
                root.visitors.push(applyVisitor);
            }
        }

        return form;
    }

    private createFormData(form: Form)
    {
        form = _.assign(new Form(), form);
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
}
