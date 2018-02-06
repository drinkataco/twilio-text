import Counter from './counter';

/**
 * Initialise modules
 */
const run = () => {
  Counter.bind('[data-bind-counter]');
};

document.addEventListener('DOMContentLoaded', run);
