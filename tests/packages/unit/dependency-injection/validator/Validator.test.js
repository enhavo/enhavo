import Validator from '@enhavo/dependency-injection/validation/Validator';
import Loader from '@enhavo/dependency-injection/loader/Loader';
import ContainerBuilder from '@enhavo/dependency-injection/container/ContainerBuilder';
import { expect, test, describe } from "vitest";

describe('Validation with circular reference', () => {
    test('should throw exception', () => {
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

        expect(() => {
            (new Validator()).validate(builder);
        }).toThrow()
    });
});

describe('Validation with missing reference', () => {
    test('should throw exception', () => {
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

        expect(() => {
            (new Validator()).validate(builder);
        }).toThrow()
    });
});
