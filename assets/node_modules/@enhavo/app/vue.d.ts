import WidgetManager from "@enhavo/dashboard/Widget/WidgetManager";
import MenuManager from "@enhavo/app/Menu/MenuManager";
import MainApp from "@enhavo/app/Main/MainApp";
import Grid from "@enhavo/app/Grid/Grid";
import ColumnManager from "@enhavo/app/Grid/Column/ColumnManager";
import BatchManager from "@enhavo/app/Grid/Batch/BatchManager";
import FilterManager from "@enhavo/app/Grid/Filter/FilterManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import DeleteApp from "@enhavo/app/Delete/DeleteApp";
import Translator from "@enhavo/core/Translator";
import Router from "@enhavo/core/Router";

declare module 'vue/types/vue' {
    interface Vue {
        $widgetManager: WidgetManager
        $menuManager: MenuManager
        $mainApp: MainApp
        $grid: Grid
        $columnManager: ColumnManager
        $batchManager: BatchManager
        $filterManager: FilterManager
        eventDispatcher: EventDispatcher,
        $deleteApp: DeleteApp,
        $translator: Translator,
        $router: Router
    }
}
