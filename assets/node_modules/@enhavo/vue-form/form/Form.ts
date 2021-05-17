import {RootFormData} from "@enhavo/vue-form/data/RootFormData";
import {FormData} from "@enhavo/vue-form/data/FormData";
import * as _ from "lodash";
import {Util} from "@enhavo/vue-form/form/Util";
import axios, {AxiosPromise,Method} from "axios";
import * as qs from "qs";

export class Form extends RootFormData
{
    public element: HTMLFormElement;
    
    public static create(data: FormData)
    {
        return _.assign(new Form(), data);
    }

    public get(name: string): FormData
    {
        let propertyChain = name.split('.');

        let searchElement: FormData = this;
        for (let property of propertyChain) {
            searchElement = this.getChild(searchElement, property);
            if (searchElement === null) {
                return null;
            }
        }
        return searchElement;
    }

    private getChild(form: FormData, name: string)
    {
        for (let child of form.children) {
            if (child.name === name) {
                return child;
            }
        }
        return null;
    }

    public setTheme(theme: object)
    {
        this.updateComponent(this, theme);
    }

    private updateComponent(form: FormData, theme: object)
    {
        if (theme.hasOwnProperty(form.component)) {
            form.component = theme[form.component];
        }

        if (theme.hasOwnProperty(form.rowComponent)) {
            form.rowComponent = theme[form.rowComponent];
        }
    }

    public serializeForm()
    {
        return Util.serializeForm(this.element);
    }

    public submitAsync(): AxiosPromise
    {
        return axios({
            method: <Method>this.element.method,
            url: this.element.action,
            data: qs.stringify(this.serializeForm()),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }

    public submit()
    {
        this.element.submit();
    }
}
