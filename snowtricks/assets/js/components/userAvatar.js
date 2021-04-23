export class UserAvatar extends HTMLElement {
    constructor() {
        super()
        // this.shadow = this.attachShadow({mode:'open'})
        const fantome = this.attachShadow({mode: 'open'});
    }
    connectedCallback() {
        const circle = document.createElement('div');
        const avatar_url = this.getAttribute('url')

        circle.style.cursor = "pointer"
        circle.setAttribute('class','h-11 w-11 rounded-full bg-gray-900')
        circle.setAttribute('id','my-avatar')

        // If user hasn't avatar display his initials
        if(avatar_url) {
            const css = document.createElement('style')

            css.textContent =
                `#my-avatar {
                    background :  var(--avatar-url) no-repeat center/100%;
                }`

            this.appendChild(css)
        } else {
            const username = this.getAttribute('username')
            const initials = document.createElement('span')

            initials.setAttribute('class','font-black text-2xl text-center text-gray-50 block pt-1')
            initials.innerHTML = username.charAt(0).toUpperCase()
            circle.appendChild(initials)
        }
        this.appendChild(circle)
    }

}
