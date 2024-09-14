import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['flash'];

  close() {
    this.flashTarget.remove();
  }
}
