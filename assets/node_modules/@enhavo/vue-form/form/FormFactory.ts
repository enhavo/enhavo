import {Form} from "@enhavo/vue-form/form/Form";
import * as _ from "lodash";
import {FormData} from "@enhavo/vue-form/data/FormData";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";

export class FormFactory
{
    private visitors: Array<FormVisitorInterface> = [];

    public create(data: FormData, theme: Theme|Array<Theme> = null)
    {
        let form = _.assign(new Form(), data);

        for (let childProperty in form.children) {
            if (form.children.hasOwnProperty(childProperty)) {
                form.children[childProperty] = this.createFormData(form.children[childProperty]);
                form.children[childProperty].parent = form;
            }
        }
        form.parent = null;


        let visitors = [];
        for (let visitor of this.visitors) {
            visitors.push(visitor);
        }
        if (theme) {
            for (let visitor of this.getThemeVisitors(theme)) {
                visitors.push(visitor);
            }
        }

        visitors = visitors.sort((a: FormVisitorInterface, b: FormVisitorInterface) => {
            return b.getPriority() - a.getPriority();
        })

        this.applyVisitors(form, visitors);

        return form;
    }

    private createFormData(data: FormData)
    {
        data = _.assign(new FormData(), data);
        for (let childProperty in data.children) {
            if (data.children.hasOwnProperty(childProperty)) {
                data.children[childProperty] = this.createFormData(data.children[childProperty]);
                data.children[childProperty].parent = data;
            }
        }
        return data;
    }

    public addVisitor(visitor: FormVisitorInterface)
    {
        this.visitors.push(visitor);
    }

    public addTheme(theme: Theme|Array<Theme>)
    {
        let visitors = this.getThemeVisitors(theme);

        for (let visitor of visitors) {
            this.addVisitor(visitor);
        }
    }

    private getThemeVisitors(theme: Theme|Array<Theme>): Array<FormVisitorInterface>
    {
        let visitors = [];

        if (Array.isArray(theme)) {
            for (let singleTheme of theme) {
                for (let visitor of singleTheme.getVisitors()) {
                    visitors.push(visitor);
                }
            }
        } else {
            for (let visitor of theme.getVisitors()) {
                visitors.push(visitor);
            }
        }

        return visitors;
    }

    private applyVisitors(form: FormData, visitors: Array<FormVisitorInterface>)
    {
        this.updateComponent(form, visitors);
        for (let key in form.children) {
            if (form.children.hasOwnProperty(key)) {
                this.applyVisitors(form.children[key], visitors);
            }
        }
    }

    private updateComponent(form: FormData, visitors: Array<FormVisitorInterface>)
    {
        let applicableVisitors = [];
        for (let visitor of visitors) {
            if (visitor.supports(form)) {
                applicableVisitors.push(visitor);
            }
        }

        for (let applicableVisitor of applicableVisitors) {
            applicableVisitor.apply(form);
        }
    }
}
