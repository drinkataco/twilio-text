// JS
import Counter from './counter';


const run = () => {
  // Initialise modules
  Counter.bind('[data-bind-counter]');
};

document.addEventListener('DOMContentLoaded', run);
