import './bootstrap';
import { createApp } from 'vue';
import InvoiceForm from './components/InvoiceForm.vue';

const app = createApp({});

app.component('InvoiceForm', InvoiceForm);

app.mount("#app");
