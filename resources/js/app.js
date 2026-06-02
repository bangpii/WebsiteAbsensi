import './bootstrap';

/*
|--------------------------------------------------------------------------
| AOS Animation
|--------------------------------------------------------------------------
*/

import AOS from 'aos';
import 'aos/dist/aos.css';

AOS.init();

/*
|--------------------------------------------------------------------------
| Feather Icons
|--------------------------------------------------------------------------
*/

import feather from 'feather-icons';

window.feather = feather;

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
});