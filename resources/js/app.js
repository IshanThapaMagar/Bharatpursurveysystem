import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import jQuery from 'jquery';
import Sortable from 'sortablejs';
import DataTable from 'datatables.net-dt';


window.Alpine = Alpine;
window.$ = jQuery;
window.jQuery = jQuery;
window.Sortable = Sortable;
window.DataTable = DataTable;


Alpine.plugin(collapse);
Alpine.start();
