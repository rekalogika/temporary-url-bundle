'use strict';

import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
  connect() {
    let element = this.element;
    setTimeout(function () {
      element.classList.add('disabled');
      element.setAttribute('disabled', 'disabled');
      element.setAttribute('tabindex', '-1');
      element.setAttribute('aria-disabled', 'true');
      element.setAttribute('title', 'This link has expired. Please refresh the page, and try again.');
      element.href = '#';
    }, 1800000);
  }
}