import EventDispatcher from "./EventDispatcher";
import View from './View';

let view = new View();
let dispatcher = new EventDispatcher(view);

export default dispatcher;