import Registry from '../Registry';
import { assert } from 'chai';

describe('core/Registry', () => {
    describe('#register', () => {
        it('should return factory and component if it was added before', () => {
            let registry = new Registry();
            let factory = new Factory();
            let component = new Component();
            registry.register('test', component, factory);
            assert.equal('factory', (<Factory>registry.getFactory('test')).name);
            assert.equal('component', (<Factory>registry.getComponent('test')).name);
        });
    });
});

class Factory
{
    public name: string = 'factory';
}

class Component
{
    public name: string = 'component';
}
