import Application from "@enhavo/app/Form/Application";
import LoadedEvent from "@enhavo/app/ViewStack/Event/LoadedEvent";

Application.getVueLoader().load();
Application.getDispatcher().dispatch(new LoadedEvent(Application.getView().getId()));
