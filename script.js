// Function to add a task
function addTask() {
    var input = document.getElementById('task-input');
    if (!input) {
        console.error("Input element not found.");
        return;
    }
    var task = input.value;
    if (task) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'todo.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            loadTasks();
        };
        xhr.send('action=add&task=' + encodeURIComponent(task));
        input.value = '';
    }
}

// Function to delete a task
function deleteTask(taskId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'todo.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        loadTasks();
    };
    xhr.send('action=delete&task_id=' + taskId);
}

// Function to load tasks
function loadTasks() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'todo.php', true);
    xhr.onload = function () {
        var taskList = document.getElementById('task-list');
        if (taskList) {
            taskList.innerHTML = this.responseText;
        } else {
            console.error("Task list element not found.");
        }
    };
    xhr.send();
}

// Bind the addTask function to the Add button
var addButton = document.getElementById('add-button');
if (addButton) {
    addButton.addEventListener('click', addTask);
}

// Bind the deleteTask function to the Delete buttons
var taskList = document.getElementById('task-list');
if (taskList) {
    taskList.addEventListener('click', function (event) {
        if (event.target.matches('.delete-button')) {
            var taskId = event.target.getAttribute('data-task-id');
            deleteTask(taskId);
        }
    });
}

// Initial load of tasks
loadTasks();


