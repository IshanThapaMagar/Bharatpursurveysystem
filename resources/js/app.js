import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Sortable from 'sortablejs';
import DataTable from 'datatables.net-dt';

window.Alpine = Alpine;
window.Sortable = Sortable;
window.DataTable = DataTable;


Alpine.plugin(collapse);
Alpine.start();
