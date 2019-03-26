class App {
    constructor() {
        this.createList = document.querySelector('button#newList');
        this.container = document.querySelector('div.lists-container');
        this.template = document.querySelector('div.todoList[data-list-id="0"]');
        this.formCreator = document.querySelector("form#createNewTODOList");
        this.iterator = 0;
        this.api = {
            list: {
                create: '/list/create',
                delete: '/list/delete',
                editTitle: 'list/put/title',
            },
            task: {
                status: '/task/status/',
                delete: '/task/delete/',
                create: '/task/create'
            }
        }
    };

    createTODOList() {
        const { list } = this.api;

        return fetch(list.create, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
            }
        })
        .then(res => res.json())
        .then(res => this.iterator = res.id)
        .then(res => this.container.appendChild(this.cloneTemplate()))
        .catch(err => console.error(err));
    }

    cloneTemplate() {
        return this.template.cloneNode(true);
    }

    reactElement(targetEl) {
        let obj = {
            _api : this.api,
            _iterator: this.iterator,
            listID() {
                if(targetEl.getAttribute('data-list-id') === '0') {
                    targetEl.setAttribute('data-list-id', this._iterator);
                    return this._iterator;
                }
                return targetEl.getAttribute('data-list-id');
            },
            listHeader: {
                element: targetEl.querySelector('div.list-header'),
                buttons: {
                    deleteList: targetEl.querySelector('button#removeList'),
                    editListTitle: targetEl.querySelector('button#editListTitle'),
                },
                title: targetEl.querySelector('div.list-title>.list-title-field'),
            },
            listBody: {
                element: targetEl.querySelector('div.list-body'),
                buttons: {
                    deleteTask: targetEl.querySelectorAll('button.task-action#delete'),
                    doneTask: targetEl.querySelectorAll('button.task-action#done'),
                },
                content: targetEl.querySelectorAll('div.list-body>ul.tasks'),
            },
            listNewTask: {
                element: targetEl.querySelector('div.list-new-task'),
                buttons: {
                    createTask: targetEl.querySelector('div#createTask>button'),
                },
                field: targetEl.querySelector('input#newTask'),
            },
            buttonsListeners() {
                const { deleteList, editListTitle } = this.listHeader.buttons;
                const { deleteTask, doneTask } = this.listBody.buttons;
                const { createTask } = this.listNewTask.buttons;
                deleteList.addEventListener('click', () => { this.removeList() });
                editListTitle.addEventListener('click', () => { this.editListTitle() });
                deleteTask.forEach((el) => {
                    el.addEventListener('click', () => this.deleteTask(el));
                });
                doneTask.forEach((el) => {
                    el.addEventListener('click', () => this.doneTask(el));
                });
                createTask.addEventListener('click', () => { this.createTask() });
            },
            removeList() {
                const { list } = this._api;
                const id = this.listID();
                fetch(list.delete + id, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    }
                });
                console.log('removelist');
            },
            editListTitle() {
                console.log('editListTitle');
            },
            deleteTask(task) {
                console.log('deleteTask');
            },
            doneTask(task) {
                console.log('doneTask');
            },
            createTask() {
                console.log('createTask');
            },
            setUp() {
                this.listID();
                this.buttonsListeners();
            }
        };
        obj.setUp();
    }

    setupNewList() {
        // promise
        const newElement = this.createTODOList();
        newElement.then((res) => {
            console.log(this.iterator);
            this.container.prepend(res);
            this.reactElement(res);
        });
    }

    init() {
        if(this.container) {
            // make new list
            this.createList.addEventListener('click', (e) => {
                this.formCreator.addEventListener('submit', e => event.preventDefault());
                this.setupNewList();
            });

            // make created list reactive
            for(let i of this.container.children) {
                this.reactElement(i);
            }
        }
    }
};

const app = new App; app.init();