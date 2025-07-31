import React from 'react';
import ReactDOM from 'react-dom/client';
import './bootstrap'; // Optional if you use it
import '../css/app.css'; // Tailwind
// import 'admin-lte/plugins/jquery/jquery.min.js';
// import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
// import 'admin-lte/dist/js/adminlte.min.js';

// import 'admin-lte/dist/css/adminlte.min.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css'


import $ from 'jquery';
window.$ = $;
window.jQuery = $;



import MyModule from './components/inventory_planning/App';

const root = ReactDOM.createRoot(document.getElementById('react-inventory-planning-new'));
root.render(<MyModule />);
