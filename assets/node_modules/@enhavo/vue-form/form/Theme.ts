import {FormVisitor, FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {Form} from "@enhavo/vue-form/model/Form";

export class Theme
{
    constructor(
        private visitors: Array<FormVisitorInterface> = []
    ) {
    }

    addVisitor(visitor: FormVisitorInterface)
    {
        this.visitors.push(visitor);
    }

    addVisitorCallback(supportCallback: (form: Form) => boolean, applyCallback: (form: Form) => Form|void, priority: number = 100)
    {
        this.visitors.push(new FormVisitor(supportCallback, applyCallback, priority));
    }

    merge(theme: Theme|Array<Theme>)
    {
        if (Array.isArray(theme)) {
            let mergedTheme = new Theme();
            for (let singleTheme of theme) {
                mergedTheme.merge(singleTheme);
            }
            theme = mergedTheme
        }

        for (let visitor of theme.visitors) {
            this.addVisitor(visitor);
        }
    }

    getVisitors(): Array<FormVisitorInterface>
    {
        return this.visitors;
    }
}
