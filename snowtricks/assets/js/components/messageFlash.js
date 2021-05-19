const template = document.createElement('template')
template.innerHTML = `
<style>
:host {
min-width: 100vw;
/*--dark-red: rgb(127, 29, 29);*/
/*--light-red: rgb(254, 202, 202);*/
/*--dark-green: rgb(6, 78, 59);*/
/*--light-green: rgb(167, 243, 208);*/
/*--dark-yellow: rgb(120, 53, 15);*/
/*--light-yellow: rgb(253, 230, 138);*/
}
@import url('https://fonts.googleapis.com/css2?family=Overpass+Mono&display=swap');

@media (min-width: 500px) {
    .flash-container {
    font-family: 'Overpass Mono', monospace;
    padding-left:18px;
    padding-top: 12px;
    position: fixed;
    bottom: 0;
    right: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    }
    .message-container {
    flex: 92%;
    flex-wrap: nowrap;
    margin-left: 24px;
    margin-right: 24px;
    }
    svg {
    flex: 8%;
    margin-right: 24px;
    }
}
@media (min-width: 1024px) {
    .flash-container {
        max-width: 60%;
        padding: 0;
        bottom: 30px;
        right: 30px;
    }
}
</style>
<div class="flash-container">
    <div class="message-container">
        <p class="message-content"><slot /></p>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 30 30" width="26px" height="26px">
        <g id="surface32101159">
        <path style=" stroke:none;fill-rule:nonzero;fill-opacity:1;" d="M 7 4 C 6.742188 4 6.488281 4.097656 6.292969 4.292969 L 4.292969 6.292969 C 3.902344 6.683594 3.902344 7.316406 4.292969 7.707031 L 11.585938 15 L 4.292969 22.292969 C 3.902344 22.683594 3.902344 23.316406 4.292969 23.707031 L 6.292969 25.707031 C 6.683594 26.097656 7.316406 26.097656 7.707031 25.707031 L 15 18.414062 L 22.292969 25.707031 C 22.683594 26.097656 23.316406 26.097656 23.707031 25.707031 L 25.707031 23.707031 C 26.097656 23.316406 26.097656 22.683594 25.707031 22.292969 L 18.414062 15 L 25.707031 7.707031 C 26.097656 7.316406 26.097656 6.683594 25.707031 6.292969 L 23.707031 4.292969 C 23.316406 3.902344 22.683594 3.902344 22.292969 4.292969 L 15 11.585938 L 7.707031 4.292969 C 7.511719 4.097656 7.257812 4 7 4 Z M 7 4 "/>
        </g>
    </svg>
</div>
`
export class MessageFlash extends HTMLElement {
    constructor ()
    {
        super()

        this.attachShadow({mode:'open'})
        this.shadowRoot.appendChild(template.content.cloneNode(true))

    }
    connectedCallback ()
    {
        const type = this.getAttribute('type')
        const flash_container = this.shadowRoot.querySelector('.flash-container')
        const close_icon = this.shadowRoot.querySelector('path')
        const message_content = this.shadowRoot.querySelector('.message-content')
        switch (type) {
            case 'error':
                flash_container.style.backgroundColor = "rgb(254, 202, 202)"
                close_icon.style.fill = "rgb(127, 29, 29)"
                message_content.style.color = "rgb(127, 29, 29)"
                break
            case 'warning':
                flash_container.style.backgroundColor = "rgb(253, 230, 138)"
                close_icon.style.fill = "rgb(120, 53, 15)"
                message_content.style.color = "rgb(120, 53, 15)"
                break
            case 'success':
                flash_container.style.backgroundColor = "rgb(15, 243, 165)"
                close_icon.style.fill = "rgb(6, 78, 59)"
                message_content.style.color = "rgb(6, 78, 59)"
                break
            default:
                flash_container.style.backgroundColor = "rgb(160, 170, 180)"
                close_icon.style.fill = "rgb(88, 66, 80)"
                message_content.style.color = "rgb(38, 66, 80)"
        }
        setTimeout(() => {this.close()}, 12000)
        close_icon.style.cursor = 'pointer'
        close_icon.addEventListener('click', (e) => {
            this.close()
        })
    }
    close() {
        this.remove()
    }
}
