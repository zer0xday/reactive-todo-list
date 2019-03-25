class App {
    constructor() {
        this.createList = document.querySelector('button#newList');
        this.container = document.querySelector('div.lists-container');
        this.template = document.querySelector('div.todoList');
    };

    getElementBefore() {
        return this.container.lastElementChild;
    }

    cloneElement() {
        return this.template.cloneNode(true);
    }

    editElement(targetEl, elBefore) {
        console.log(targetEl);
        console.log(elBefore);
    }

    init() {
        if(this.container) {
            this.createList.addEventListener('click', () => {
                const lastEl = this.getElementBefore();
                const newElement = this.container.appendChild(this.cloneElement());
                this.editElement(newElement, lastEl);
            })
        }
    }
};

const app = new App; app.init();