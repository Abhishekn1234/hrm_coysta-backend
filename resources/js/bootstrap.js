import _ from 'lodash';
import Popper from 'popper.js';
import $ from 'jquery';
import 'bootstrap';
import axios from 'axios';

window._ = _;
window.Popper = Popper;
window.$ = window.jQuery = $;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// If you want to use Laravel Echo & Pusher later, you can import them similarly using `import`

// Example:
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;
// window.Echo = new Echo({ /* config here */ });
