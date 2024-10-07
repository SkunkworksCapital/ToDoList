// Handle keypress event, check for Enter key
function handleKeyPress(event) {
    if (event.key === 'Enter') {
        addTask();
    }
}

// Fetch tasks from the server
function fetchTasks() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'tasks.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('task-list').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Add a new task
function addTask() {
    const taskInput = document.getElementById('new-task').value;
    if (taskInput === '') {
        alert('Please enter a task');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'tasks.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            fetchTasks();
            document.getElementById('new-task').value = ''; // Clear input field
        } else {
            alert('Failed to add task');
        }
    };
    xhr.send('action=add&task=' + encodeURIComponent(taskInput)); // Use encodeURIComponent for safety
}

// Delete a task
function deleteTask(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'tasks.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            fetchTasks();
        }
    };
    xhr.send('action=delete&id=' + id);
}

// Mark task as completed
function completeTask(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'tasks.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            fetchTasks();
        }
    };
    xhr.send('action=complete&id=' + id);
}

// Load tasks when the page loads
window.onload = fetchTasks;
