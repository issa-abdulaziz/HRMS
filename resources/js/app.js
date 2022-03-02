require('./bootstrap');
window.$ = window.jQuery = require('jquery');
import 'datatables.net-dt';
import 'datatables.net-responsive-dt';
import 'datatables.net-buttons-dt';
//import 'jszip';
import jsZip from 'jszip';
//import 'pdfmake';
import 'datatables.net-buttons/js/buttons.colVis.js';
import 'datatables.net-buttons/js/buttons.html5.js';
import 'datatables.net-buttons/js/buttons.print.js';
import 'datatables.net-buttons/js/buttons.flash.min';

// This line was the one missing
window.JSZip = jsZip;

import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;