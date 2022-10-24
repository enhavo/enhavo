import {RootFormData} from "@enhavo/vue-form/data/RootFormData";
import {FormData} from "@enhavo/vue-form/data/FormData";
import * as _ from "lodash";
import {Util} from "@enhavo/vue-form/form/Util";
import axios, {AxiosPromise,Method} from "axios";
import {Theme} from "@enhavo/vue-form/form/Theme";

export class Form extends RootFormData
{
    public element: HTMLFormElement;
    
    public static create(data: FormData)
    {
        let form = _.assign(new Form(), data);

        for (let childProperty in form.children) {
            if (form.children.hasOwnProperty(childProperty)) {
                form.children[childProperty] = Form.createFormData(form.children[childProperty]);
                form.children[childProperty].parent = form;
            }
        }

        form.parent = null;

        return form;
    }

    private static createFormData(data: FormData)
    {
        data = _.assign(new FormData(), data);
        for (let childProperty in data.children) {
            if (data.children.hasOwnProperty(childProperty)) {
                data.children[childProperty] = Form.createFormData(data.children[childProperty]);
                data.children[childProperty].parent = data;
            }
        }
        return data;
    }

    public addTheme(theme: Theme|Array<Theme>)
    {
        if (Array.isArray(theme)) {
            let mergedTheme = new Theme();
            for (let singleTheme of theme) {
                mergedTheme.merge(singleTheme);
            }
            mergedTheme.apply(this);
        } else {
            theme.apply(this);
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
