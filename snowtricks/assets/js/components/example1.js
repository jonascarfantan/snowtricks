/**
 * Example: basic usage of a webcomponent constructor
 */
class Example1 extends HTMLElement {
    // Permet d'observer des attributs qui pourront être manipulé dans attributeChangedCallback(name, old_value, new_value) {}
    static get observedAttributes() { return ['attribute_name']; }

    constructor() {
        // calling super first to “establish the correct prototype chain and this value before any further code is run”
        super();

        // setting some initial state
        this.counter = 0;
        this.active = false;

        // adding an event listener
        this.addEventListener("click", this._clickHandler);

        // bind the context of "this" to our _clickHandler method that we'll set up later
        this._clickHandler = this._clickHandler.bind(this);

        // attaching a shadow root and adding some style and markup
        this.attachShadow({ mode: "open" });
        this.shadowRoot.innerHTML = `
    <style>
      :host {
        display: inline-block;
      }
    </style>
    <div>This is a really awesome component!</div>
  `;
    }

    connectedCallback() {

    }
    disconectedCallback() {

    }

    attributeChangedCallback() {

    }


}
