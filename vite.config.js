import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.jsx',
        'resources/js/app_pos.jsx',
        'resources/css/app.css',
        'resources/js/app_products.jsx',
        'resources/js/app_inventory.jsx',
        'resources/js/app_quotation.jsx',
        'resources/js/app_inventory_planning.jsx',
        'resources/js/app_dashboard.jsx',
        'resources/js/app_people.jsx'
      ],
      refresh: true,
    }),
    react(),
  ],
  resolve: {
    alias: {
      // 🔧 This line tells Vite where the worker is
      'pdfjs-dist/build/pdf.worker.entry': 'pdfjs-dist/build/pdf.worker.min.js',
    },
  },
});
