export class Menu {
    parent = null;
    state = false;
    openToggleSVG = `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 10l6 6l6-6" />
        </svg>
    `;
    closeToggleSVG = `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 14l6-6l6 6" />
        </svg>
    `
    constructor(elementTagName){
        this.parent = document.querySelector(elementTagName);

        if(!this.parent) throw new Error(`Cant find any element with ${elementTagName}`);
        this.initialize();
    }

    initialize(){
        if(!this.parent) return;

        const toolbox = this.parent.querySelector('.toolbox');
        const toggle = this.parent.querySelector('#toggle');

        const arrow = document.createElement('div');
        arrow.classList.add("arrow");
        toggle.appendChild(arrow);

        this.handleToggle(toggle);
        this.handleToolbox(toolbox);

        toggle.onclick = (e) => {
            this.open();
            this.handleToggle(e.currentTarget);
            this.handleToolbox(toolbox);
        }
    }

    open() {
        this.state = !this.state;
    }

    handleToggle(elementToggle){
        const arrow = elementToggle.querySelector(".arrow");
        console.log(arrow);
        if(!arrow) return;
        
        (this.state)
            ?
            arrow.innerHTML = this.closeToggleSVG
            :
            arrow.innerHTML = this.openToggleSVG;
    }

    handleToolbox(elementToolbox){
        elementToolbox.classList.add('hidden');
        if(this.state) elementToolbox.classList.remove('hidden');
    }
}