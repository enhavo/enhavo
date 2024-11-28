
export default class Validator
{
    /**
     * @param {ContainerBuilder} builder
     */
    validate(builder) {
        this._checkServiceExists(builder);
        this._checkCircularReferences(builder);
    }

    _getServiceNameDependencies(definition, includeCalls = false) {
        let names = [];
        names = names.concat(this._getServiceNames(definition.getArguments()));

        if(includeCalls) {
            for (let call of definition.getCalls()) {
                names = names.concat(this._getServiceNames(call.getArguments()));
            }
        }

        return names;
    }

    _getServiceNames(argumentList) {
        let names = [];
        for (let argument of argumentList) {
            if(argument.getType() === 'service') {
                names.push(argument.getValue());
            }
        }
        return names;
    }

    _checkServiceExists(builder) {
        for (let definition of builder.getDefinitions()) {
            let dependencies = this._getServiceNameDependencies(definition, true);
            for (let dependency of dependencies) {
                if(!builder.hasDefinition(dependency)) {
                    throw 'Service "'+dependency+'" does not exist. Defined in "'+definition.getName()+'"';
                }
            }
        }
    }

    _checkCircularReferences(builder) {
        for (let definition of builder.getDefinitions()) {
            this._checkCircularReferenceRecursive(builder, definition, [definition.getName()]);
        }
    }

    _checkCircularReferenceRecursive(builder, definition, dependencyList) {
        let dependencies = this._getServiceNameDependencies(definition);
        for (let dependency of dependencies) {
            if (dependencyList.includes(dependency)) {

                throw 'Circular references detected ['+dependencyList.join(' -> ')+' -> '+dependency+']';
            }

            let nodeDependencies = this._copyArray(dependencyList);
            nodeDependencies.push(dependency);

            let nodeDefinition = builder.getDefinition(dependency);

            this._checkCircularReferenceRecursive(builder, nodeDefinition, nodeDependencies);
        }
    }

    _copyArray(array) {
        let newArray = [];
        for (let entry of array) {
            newArray.push(entry);
        }
        return newArray;
    }
}
