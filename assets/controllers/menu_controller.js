import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['menu'];

  close() {
    this.menuTarget.classList.add('hidden');
  }

  open() {
    this.menuTarget.classList.remove('hidden');
  }
}
