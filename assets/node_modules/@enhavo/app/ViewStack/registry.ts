import ViewRegistry from './ViewRegistry'
import IframeViewFactory from './Factory/IframeViewFactory'
import IframeViewComponent from './Components/IframeViewComponent.vue'
import AjaxViewFactory from './Factory/AjaxViewFactory'
import AjaxViewComponent from './Components/AjaxViewComponent.vue'


let registry = new ViewRegistry();

registry.register('iframe-view', IframeViewComponent, new IframeViewFactory());
registry.register('ajax-view', AjaxViewComponent, new AjaxViewFactory());

export default registry;