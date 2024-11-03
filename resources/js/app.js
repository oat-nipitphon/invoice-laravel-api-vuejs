import './bootstrap';

import { createApp } from 'vue'
import App from './Pages/App.vue'
import router from './router/index'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// // import Bootstrap 5
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle';

const app = createApp(App);

app.use(router);
app.use(VueSweetalert2);
app.mount('#app');
