class Counter {
  // const
  //
  static bind(query) {
    document.querySelectorAll(query).forEach((e) => {
      new Counter(e, e.dataset.bindCounter);
    });
  }

  /**
   * [constructor description]
   *
   * @param {HTMLElement} triggerElement   Element which triggers the counter
   * @param {HTMLElement} containerElement Main counter container element (or actual element)
   */
  constructor(triggerElement, containerElement) {
    this.triggerElement = triggerElement;

    // Get Max Length from data-bind-counter-max or maxLength attribute
    this.maxLength = this.triggerElement.dataset.bindCounterMax || this.triggerElement.getAttribute('maxLength');

    // Find out main count element
    this.containerElement = document.querySelector(containerElement);

    this.counterElement = (this.containerElement.classList.contains(this.classCounter)) ?
      this.containerElement :
      this.containerElement.querySelector(`.${this.classCounter}`);

    // Set Max length to element
    this.setMaxValue();

    // Add Event Listeners
    this.triggerElement.addEventListener('change', this.counterUpdate.bind(this));
    this.triggerElement.addEventListener('keyup', this.counterUpdate.bind(this));
  }

  /**
   * Get Max Value of element and propagate it to counter element
   */
  setMaxValue() {
    this.maxElement = this.counterElement.querySelector(`.${this.classCounterMax}`);
    this.maxElement.innerHTML = this.maxLength;

    // Also get count Element
    this.countElement = this.counterElement.querySelector(`.${this.classCounterCount}`);
  }

  counterUpdate(ev) {
    const inputLength = this.triggerElement.value.length || 0;

    this.countElement.innerHTML = inputLength;

    if (inputLength > this.maxLength) {
      this.counterElement.classList.add(this.classCounterOver);
    } else {
      this.counterElement.classList.remove(this.classCounterOver);
    }
  }

}

Counter.prototype.classCounter = 'counter';
Counter.prototype.classCounterOver = 'counter--danger';
Counter.prototype.classCounterCount = 'counter__chars-count'
Counter.prototype.classCounterMax = 'counter__chars-max'

export default Counter;
