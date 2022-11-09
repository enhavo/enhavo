import {FormFactory} from "@enhavo/vue-form/form/FormFactory"
import {RootForm} from "@enhavo/vue-form/model/RootForm";
import {TestVisitor} from "@enhavo/vue-form/tests/mock/TestVisitor";
import {Theme} from "@enhavo/vue-form/form/Theme";
import {TestForm} from "@enhavo/vue-form/tests/mock/TestForm";
import {FormVisitor} from "@enhavo/vue-form/form/FormVisitor";
import {Form} from "@enhavo/vue-form/model/Form";
const assert = require("chai").assert;

describe('vue-form/form/FormFactory', () => {
    describe('On create', () => {
        it('it should create a root form object', () => {
            let factory = new FormFactory();
            let form = factory.create({component: 'test'});
            assert.isTrue(form instanceof RootForm)
            assert.equal('test', form.component);
        });
    });

    describe('After adding some visitors', () => {
        let factory = new FormFactory();
        factory.addVisitor(new TestVisitor('foo', 100));
        factory.addVisitors([new TestVisitor('hello', 80)]);
        factory.addTheme(new Theme([new TestVisitor('bar', 90)]));

        it('it should create a manipulate form with visitor parameter', () => {
            let form = <TestForm>factory.create({component: 'test'}, new TestVisitor('world', 70));
            assert.equal('foo', form.testValues[0]);
            assert.equal('bar', form.testValues[1]);
            assert.equal('hello', form.testValues[2]);
            assert.equal('world', form.testValues[3]);
        });

        it('it should execute visitors only once', () => {
            let visitor = new TestVisitor('world', 70);
            let form = <TestForm>factory.create({component: 'test'}, [visitor, visitor]);
            assert.equal(4, form.testValues.length);
        });

        it('it should create a manipulate form with visitor array', () => {
            let form = <TestForm>factory.create({component: 'test'}, [new TestVisitor('world', 70)]);
            assert.equal('world', form.testValues[3]);
        });

        it('it should create a manipulate form with theme parameter', () => {
            let form = <TestForm>factory.create({component: 'test'}, new Theme([new TestVisitor('world', 70)]));
            assert.equal('world', form.testValues[3]);
        });

        it('it should store the visitors in the root node', () => {
            let form = <TestForm>factory.create({component: 'test'}, new TestVisitor('world', 70));
            assert.equal(4, (<RootForm>form.getRoot()).visitors.length);
        });

        it('it should store the visitors only once at the root node', () => {
            let form = <RootForm>factory.create({component: 'test'}, new TestVisitor('world', 70));
            form = <RootForm>factory.create({component: 'test'}, form.visitors, form);
            assert.equal(4, (<RootForm>form.getRoot()).visitors.length);
        });
    });

    describe('After adding some visitors that replace a form', () => {
        let factory = new FormFactory();
        factory.addVisitor(new FormVisitor((form: Form) => {
            return form.name == '0';
        }, (form: RootForm) => {
            let newForm =  new TestForm('new_0');
            newForm.parent = form.parent;
            newForm.children = form.children;
            return newForm;
        }));

        factory.addVisitor(new FormVisitor((form: Form) => {
            return form.name == '1';
        }, (form: Form) => {
            let newForm = new TestForm('new_1');
            newForm.parent = form.parent;
            newForm.children = form.children;
            return newForm;
        }));

        let data = {
            name: '0',
            children: [
                {
                    name: '1',
                }
            ]
        }

        it('it should be really replaced', () => {
            let form = <TestForm>factory.create(data);
            assert.equal('new_0', form.name);
            assert.isTrue(form instanceof TestForm);

            assert.equal('new_1', form.get('new_1').name);
            assert.isTrue(form.get('new_1') instanceof TestForm);
        });
    });

    describe('On create with form tree', () => {
        let factory = new FormFactory();

        let data = {
            component: '0',
            name: '0',
            children: [
                {
                    component: '1',
                    name: '1',
                },
                {
                    component: '2',
                    name: '2',
                    children: [
                        {
                            component: '21',
                            name: '21',
                        }
                    ]
                }
            ]
        }

        it('it should set the correct parents', () => {
            let form = <TestForm>factory.create(data);
            assert.isNull( form.parent);
            assert.isTrue( form.get('1').parent === form);
            assert.isTrue( form.get('2').parent === form);
            assert.isTrue( form.get('2.21').parent === form.get('2'));
        });
    });
});
