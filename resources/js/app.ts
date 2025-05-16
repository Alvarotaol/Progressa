import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import '../css/app.css';
import 'highlight.js/styles/github.css';

createApp(App).use(router).mount('#app');
