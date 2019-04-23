import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import AppInterface from "@enhavo/app/AppInterface";
import DataLoader from "@enhavo/app/DataLoader";
import VueLoader from "@enhavo/app/VueLoader";
import View from "@enhavo/app/View/View";
import Router from "@enhavo/core/Router";
import Translator from "@enhavo/core/Translator";
import StateManager from "@enhavo/app/State/StateManager";

export default interface ApplicationInterface
{
    getEventDispatcher(): EventDispatcher;

    getApp(): AppInterface;

    getDataLoader(): DataLoader;

    getVueLoader(): VueLoader;

    getView(): View;

    getRouter(): Router

    getTranslator(): Translator

    getStateManager(): StateManager
}