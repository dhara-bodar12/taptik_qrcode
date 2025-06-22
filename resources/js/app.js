/*import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();*/

import { createApp } from 'vue';
import OfferForm from './components/OfferForm.vue';

const app = createApp({});
app.component('offer-form', OfferForm);
app.mount('#app');