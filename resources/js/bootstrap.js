import axios from 'axios';
import mitt from 'mitt';

window.mitt = mitt();
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
