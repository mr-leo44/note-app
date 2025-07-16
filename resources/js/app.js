import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';
import 'flowbite/dist/flowbite.turbo.js';
import * as DataTable from 'simple-datatables';
window.DataTable = DataTable.default || DataTable;

window.Alpine = Alpine;
Alpine.start();
