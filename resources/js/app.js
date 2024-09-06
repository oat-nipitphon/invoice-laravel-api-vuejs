import './bootstrap';

import { createApp } from 'vue'
import app from './components/app.vue'
import router from './router/index.js'

// // import Bootstrap 5
// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap/dist/js/bootstrap.bundle';

createApp(app).use(router).mount('#app')
