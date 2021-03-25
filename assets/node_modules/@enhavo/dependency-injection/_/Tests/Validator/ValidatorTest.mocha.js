const Validator = require('@enhavo/dependency-injection/Validation/Validator');
const Loader = require('@enhavo/dependency-injection/Loader/Loader');
const ContainerBuilder = require('@enhavo/dependency-injection/Container/ContainerBuilder');
const chai = require("chai");

describe('dependency-injection/Validation/Validator', () => {
    describe('Validation with circular reference', () => {
        it('should throw exception', () => {
            let builder = new ContainerBuilder();
            (new Loader).load({
                services: {
                    firstService: {
                        arguments: [
                            'secondService'
                        ]
                    },
                    secondService: {
                        arguments: [
                            'firstService'
                        ]
                    }
                }
            }, null, builder, __dirname);

            chai.expect(() => {
                (new Validator()).validate(builder);
            }).to.throw();
        });
    });

    describe('Validation with missing reference', () => {
        it('should throw exception', () => {
            let builder = new ContainerBuilder();
            (new Loader).load({
                services: {
                    firstService: {
                        arguments: [
                            'secondService'
                        ]
                    }
                }
            }, null, builder, __dirname);

            chai.expect(() => {
                (new Validator()).validate(builder);
            }).to.throw();
        });
    });
});