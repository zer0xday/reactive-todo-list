class App {
    constructor() {
        this.createList = document.querySelector('button#newList');
        this.container = document.querySelector('div.lists-container');
        this.template = document.querySelector('div.todoList[data-list-id="0"]');
        this.iterator = {};
        this.api = {
            list: {
                create: '/list/create',
                remove: '/list/remove/',
                edit: {
                    title: '/list/edit/title/',
                }
            },
            task: {
                status: '/task/edit/status/',
                remove: '/task/remove/',
                create: '/task/create/'
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
        .then(res => this.iterator = res)
        .then(res => this.container.appendChild(this.cloneTemplate()))
        .catch(err => console.error(err));
    }

    cloneTemplate() {
        return this.template.cloneNode(true);
    }

    reactElement(targetEl) {
        let list = {
            /* with'_' prefix -> cloned from class constructor */
            _el : targetEl,
            _api : this.api,
            _iterator: this.iterator.list,
            _taskIterator: this.iterator.task,
            setID(element, attribute, idSetter) {
                if(element) {
                    if(!element.getAttribute(attribute) || element.getAttribute(attribute) === '0') {
                        element.setAttribute(attribute, idSetter);
                    }
                    return element.getAttribute(attribute);
                }
                return null;
            },
            ID: targetEl.getAttribute('data-list-id'), // it needs to be set in setUp() method
            Header: {
                element: targetEl.querySelector('div.list-header'),
                buttons: {
                    removeList: targetEl.querySelector('button#removeList'),
                    editListTitle: targetEl.querySelector('button#editListTitle'),
                    saveListTitle: targetEl.querySelector('button#saveListTitle')
                },
                title: targetEl.querySelector('input.list-title-field'),
            },
            Body: {
                element: targetEl.querySelector('div.list-body'),
                buttons: {
                    removeTask: targetEl.querySelectorAll('button.task-action#delete'),
                    doneTask: targetEl.querySelectorAll('button.task-action#done'),
                    halfDoneTask: targetEl.querySelectorAll('button.task-action#halfDone'),
                },
                status: {
                    done: targetEl.querySelector('button.task-action#done'),
                    halfDone: targetEl.querySelector('button.task-action#halfDone'),
                    remove: targetEl.querySelector('button.task-action#delete'),
                },
                tasks: targetEl.querySelector('ul.tasks'),
                templateTask: this.template.querySelector('ul.tasks').children[0],
                firstTask: targetEl.querySelector('ul.tasks').children[0],
            },
            NewTask: {
                element: targetEl.querySelector('div.list-new-task'),
                buttons: {
                    createTask: targetEl.querySelector('div#createTask>button'),
                },
                field: targetEl.querySelector('input#newTask'),
            },

            buttonsListeners() {
                // edit / delete list
                const { removeList, editListTitle, saveListTitle } = this.Header.buttons;
                removeList.addEventListener('click', () => { this.removeList() });
                editListTitle.addEventListener('click', () => { this.toggleListTitleInput(true) });
                saveListTitle.addEventListener('click', () => {
                    this.editListTitle().then(() => this.toggleListTitleInput(false));
                });

                // edit / delete task
                const { removeTask, doneTask, halfDoneTask } = this.Body.buttons;
                removeTask.forEach((el, index) => {
                    el.addEventListener('click', () => this.deleteTask(this.Body.tasks.children[index]));
                });
                doneTask.forEach((el, index) => {
                    el.addEventListener('click', () => this.changeTaskStatus('done', this.Body.tasks.children[index]));
                });
                halfDoneTask.forEach((el, index) => {
                    el.addEventListener('click', () => this.changeTaskStatus('halfDone', this.Body.tasks.children[index]));
                });
                // create task
                const { createTask } = this.NewTask.buttons;
                createTask.addEventListener('click', () => { this.createTask() });
            },

            /* UI Calls */
            toggleListTitleInput(bool) {
                const { title, buttons: { saveListTitle } } = this.Header;

                if(bool) {
                    saveListTitle.classList.add('show');
                } else {
                    saveListTitle.classList.remove('show');
                }

                if(saveListTitle.classList.contains('show')) {
                    title.readOnly = false;
                    title.focus();
                } else {
                    title.readOnly = true;
                    title.blur();
                }
            },

            destroyList() {
                this._el.remove();
            },

            /* API calls */
            removeList() {
                const { remove } = this._api.list;

                return fetch(remove + this.ID, {
                    method: 'POST',
                })
                .then(res => res.json())
                .then(() => this.destroyList())
                .catch(err => console.error(err));
            },

            editListTitle() {
                const { edit } = this._api.list;
                const { title } = this.Header;
                const formData = new FormData();
                formData.append('title', title.value);

                return fetch(edit.title + this.ID, {
                    method: 'POST',
                    body: formData,
                })
                .then(res => res.json())
                .then(res => { title.value = res.title })
                .catch(err => console.error(err));
            },

            deleteTask(task) {
                const { remove } = this._api.task;
                const taskId = task.getAttribute('data-task-id');

                return fetch(remove + taskId, {
                    method: 'POST'
                })
                .then(res => res.json())
                .then(res => task.remove())
                .catch(err => console.error(err));
            },

            changeTaskStatus(status, taskElement) {
                const taskId = taskElement.getAttribute('data-task-id');

                const changeStatus = (value, taskId) => {
                    const { task } = this._api;
                    let statusValue = taskElement.querySelector('.status>small');
                    let taskValue = taskElement.querySelector('.content>p');
                    const formData = new FormData;
                    formData.append('status_id', value);

                    return fetch(task.status + taskId, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        statusValue.innerHTML = res.status_name;
                        res.status_name === 'Done'
                            ? taskValue.innerHTML = `<s>${res.task_value}</s>`
                            : taskValue.innerHTML = res.task_value;
                    })
                    .catch(err => console.error(err))
                };
                switch(status) {
                    case 'done':
                        return changeStatus(3, taskId);

                    case 'halfDone':
                        return changeStatus(2, taskId);

                    default: break;
                };
            },

            createTask() {
                const buildTask = (id, content) => {
                    const { tasks, templateTask } = this.Body;

                    let newTask = templateTask.cloneNode(true);
                    newTask.setAttribute('data-task-id', id);
                    newTask.id = tasks.children.length + 1;
                    newTask.querySelector('.content>p').innerHTML = content;

                    tasks.appendChild(newTask);
                    const remove = newTask.querySelector('button#delete');
                    const halfDone = newTask.querySelector('button#halfDone');
                    const done = newTask.querySelector('button#done');

                    remove.addEventListener('click', () => this.deleteTask(newTask));
                    halfDone.addEventListener('click', () => this.changeTaskStatus('halfDone', newTask));
                    done.addEventListener('click', () => this.changeTaskStatus('done', newTask));
                }
                const { create } = this._api.task;
                const { field } = this.NewTask;
                const formData = new FormData;
                formData.append('task_value', field.value);

                return fetch(create + this.ID, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => res !== null ? buildTask(res.task_id, field.value) : null)
                .then(res => field.value = '')
                .catch(err => console.error(err));
            },

            /* setUp object */
            setUp() {
                this.ID = this.setID(this._el, 'data-list-id', this._iterator);
                this.setID(this.Body.firstTask, 'data-task-id', this._taskIterator);
                this.buttonsListeners();
            }
        };
        list.setUp();
    }

    setupNewList() {
        // promise
        const newElement = this.createTODOList();
        newElement.then((res) => {
            this.container.prepend(res);
            this.reactElement(res);
        });
    }

    init() {
        if(this.container) {
            // make new list
            this.createList.addEventListener('click', (e) => {
                this.setupNewList();
            });
            // make created list reactive
            // -1 in length bcuz last element is template
            if(this.container.children.length > 1) {
                for (let i = 0; i < this.container.children.length - 1; i++) {
                    this.reactElement(this.container.children[i]);
                }
            }
        }
    }
};

const app = new App; app.init();