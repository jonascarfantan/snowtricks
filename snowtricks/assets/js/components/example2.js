export class Example2 extends HTMLElement {
    constructor() {
        // We can only Manipulate shadow DOM in constructor and absolutely not the Light DOM
        super();
        const open = false;
        this.shadow = this.attachShadow({mode: 'open'})
        //With mode: 'open' we can access this element from outside use =>
        // document.querySelector('user-dropdown').shadowRoot.querySelector('img');
        this.style = document.createElement('style');
        this.style.textContent = `
        <style>
        /* Select the host of our shadow DOM */
        /* We can overload css rules here */
        /*:host {*/
        /* --avatar_border_color: red;*/
        /*}*/
        img {
            /* we can use a css variable defined as :root { --my-var: value;} in the light DOM*/
            border: 2px solid var(--avatar_border_color);
            height: 12px;
            border-radius: 100%;
        }
        </style>
        `
        this.img = document.createElement('img');
        this.setAttribute('src', this.getAttribute('name'));
        this.setAttribute('alt', this.getAttribute('alt'));

        }
    connectedCallback() {
        // Create HTML markup here if we don't need a Shadow DOM
        // this.innerHTML = `<img src="${this.getAttribute('name')}" alt="${this.getAttribute('alt')}">`;
    }
}
