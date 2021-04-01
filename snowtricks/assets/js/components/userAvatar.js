export class UserAvatar extends HTMLElement {
    constructor() {
        super()
        // this.shadow = this.attachShadow({mode:'open'})

    }
    connectedCallback() {
        const circle = document.createElement('div');
        const avatar_url = this.getAttribute('url')

        circle.style.cursor = "pointer"
        circle.setAttribute('class','h-11 w-11 rounded-full bg-gray-900')

        // If user hasn't avatar display his initials
        if(avatar_url) {
            const css = document.createElement('style')

            circle.setAttribute('id','my-avatar')
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


    wipTestWithShadowDom() {
        const circle = document.createElement('div');
        // const avatar_url = this.shadow.host.getAttribute('url')
        // console.log(avatar_url)
        // circle.style.cursor = "pointer"
        // circle.setAttribute('class','h-11 w-11 rounded-full bg-gray-900')
        //
        // // If user hasn't avatar display his initials
        // if(avatar_url) {
        //     const css = document.createElement('style')
        //
        //     circle.setAttribute('id','my-avatar')
        //     //TODO TROUVER COMMENT PASSER CETTE PUTAIN D'URL DANS LE SHADOW PUTAIN DE DOM
        //     css.textContent =
        //         `
        //         :root { --avatar-url: url( {{ user ? (user.avatar ? user.avatar.path : null) : null }} ); }
        //         #my-avatar {
        //             background :  var(--avatar-url) no-repeat center/100%;
        //         }
        //         `
        //
        //     this.shadow.appendChild(css)
        // } else {
        //     const username = this.shadow.host.getAttribute('username')
        //     const initials = document.createElement('span')
        //
        //     initials.setAttribute('class','font-black text-2xl text-center text-gray-50 block pt-1')
        //     initials.innerHTML = username.charAt(0).toUpperCase()
        //     circle.appendChild(initials)
        // }
        // this.shadow.appendChild(circle)
    }

}
