export class ExpressionLanguage
{
    private values = {};

    constructor()
    {
        this.addDefaultValues();
    }

    evaluate(expression: string, values: object = {})
    {
        const assignValues = Object.assign({}, this.values, values);

        if (expression.startsWith('expr:')) {
            let exp = expression.substring(5);
            try {
                return this.evaluateExpression(exp, assignValues);
            } catch (e) {
                throw 'ExpressionLanguage: Error for expression "'+exp+'" with message: '+ e;
            }

        }

        return expression;
    }

    evaluateObject(expression: object, values: object = {})
    {
        for (let key in expression) {
            if (expression.hasOwnProperty(key)) { // This checks if the key is the object's own property
                if (typeof expression[key] === 'string') {
                    expression[key] = this.evaluate(expression[key], values);
                } else if (typeof expression[key] === 'object') {
                    expression[key] = this.evaluateObject(expression[key], values);
                }
            }
        }

        return expression;
    }

    addValue(name: string, value: any)
    {
        this.values[name] = value;
    }

    private addDefaultValues()
    {
        this.addValue('query', {
            get: (name: string) => {
                return new URLSearchParams(window.location.search).get(name)
            },
            has: (name: string) => {
                return new URLSearchParams(window.location.search).get(name) !== null
            }
        })
    }

    private evaluateExpression(code: string, args: object = {})
    {
        // Call is used to define where "this" within the evaluated code should reference.
        // eval does not accept the likes of eval.call(...) or eval.apply(...) and cannot
        // be an arrow function
        return function evaluateEval() {
            // Create an args definition list e.g. "arg1 = this.arg1, arg2 = this.arg2"
            const argsStr = Object.keys(args)
                .map(key => `${key} = this.${key}`)
                .join(',');
            const argsDef = argsStr ? `let ${argsStr};` : '';

            return eval(`${argsDef}${code}`);
        }.call(args);
    }
}
