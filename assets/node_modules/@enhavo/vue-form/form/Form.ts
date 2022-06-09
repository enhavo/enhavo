import {RootFormData} from "@enhavo/vue-form/data/RootFormData";
import {FormData} from "@enhavo/vue-form/data/FormData";
import * as _ from "lodash";
import {Util} from "@enhavo/vue-form/form/Util";
import axios, {AxiosPromise,Method} from "axios";
import * as qs from "qs";
import {Theme} from "@enhavo/vue-form/form/Theme";

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
            if (searchElement.children.hasOwnProperty(property)) {
                searchElement = searchElement.children[property];
            } else {
                return null;
            }
        }
        return searchElement;
    }

    public setTheme(theme: Theme)
    {
        theme.apply(this);
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
            data: this.serializeForm(),
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
