import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import AppInterface from "@enhavo/app/AppInterface";
import DataLoader from "@enhavo/app/DataLoader";
import VueLoader from "@enhavo/app/VueLoader";
import View from "@enhavo/app/View/View";
import Router from "@enhavo/core/Router";
import Translator from "@enhavo/core/Translator";
import ActionManager from "@enhavo/app/Action/ActionManager";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ModalManager from "@enhavo/app/Modal/ModalManager";
import ModalRegistry from "@enhavo/app/Modal/ModalRegistry";

export default interface ApplicationInterface
{
    getEventDispatcher(): EventDispatcher;

    getApp(): AppInterface;

    getDataLoader(): DataLoader;

    getVueLoader(): VueLoader;

    getView(): View;

    getRouter(): Router

    getTranslator(): Translator

    getActionManager(): ActionManager

    getActionRegistry(): ActionRegistry

    getModalManager(): ModalManager

    getModalRegistry(): ModalRegistry
}