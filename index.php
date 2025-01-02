<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Sage List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 1.1rem;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 2rem;
        }
        .task-list {
            margin-top: 2rem;
        }
        .task-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 0.5rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .task-item:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .task-item.completed {
            background-color: #f8f9fa;
            opacity: 0.8;
        }
        .task-item.completed .task-text {
            text-decoration: line-through;
            color: #6c757d;
        }
        .task-text {
            flex-grow: 1;
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        .button-group {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }
        .task-input-edit {
            flex-grow: 1;
            padding: 0.5rem;
            font-size: 1.1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4 text-primary">Todo List</h1>
        
        <div class="input-group mb-4">
            <input type="text" id="newTask" class="form-control form-control-lg" 
                   placeholder="Add new task and press Enter">
        </div>
        
        <div class="task-list" id="taskList">
            <?php
            $stmt = $db->query("SELECT * FROM tasks ORDER BY created_at DESC");
            while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $completed = $task['status'] === 'completed' ? 'completed' : '';
                echo "<div class='task-item {$completed}' data-id='{$task['id']}'>";
                echo "<span class='task-text'>" . htmlspecialchars($task['task']) . "</span>";
                echo "<div class='button-group'>";
                if ($task['status'] !== 'completed') {
                    echo "<button class='btn btn-outline-primary btn-sm' onclick='editTask({$task['id']})'>";
                    echo "<i class='fas fa-edit'></i> Edit</button>";
                    echo "<button class='btn btn-outline-success btn-sm' onclick='completeTask({$task['id']})'>";
                    echo "<i class='fas fa-check'></i> Complete</button>";
                }
                echo "</div></div>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('newTask').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                const task = this.value.trim();
                fetch('add_task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'task=' + encodeURIComponent(task)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
                this.value = '';
            }
        });

        function editTask(id) {
            const taskItem = document.querySelector(`.task-item[data-id="${id}"]`);
            const taskText = taskItem.querySelector('.task-text');
            const originalText = taskText.textContent;
            
            const input = document.createElement('input');
            input.type = 'text';
            input.value = originalText;
            input.className = 'form-control task-input-edit';
            
            const saveBtn = document.createElement('button');
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Save';
            saveBtn.className = 'btn btn-primary btn-sm';
            
            const cancelBtn = document.createElement('button');
            cancelBtn.innerHTML = '<i class="fas fa-times"></i> Cancel';
            cancelBtn.className = 'btn btn-secondary btn-sm';
            
            const buttonGroup = taskItem.querySelector('.button-group');
            const originalButtons = buttonGroup.innerHTML;
            
            taskText.replaceWith(input);
            buttonGroup.innerHTML = '';
            buttonGroup.appendChild(saveBtn);
            buttonGroup.appendChild(cancelBtn);
            
            input.focus();
            
            const saveChanges = () => {
                const newText = input.value.trim();
                if (newText) {
                    fetch('update_task.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${id}&task=${encodeURIComponent(newText)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                }
            };
            
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveChanges();
                }
            });
            
            saveBtn.onclick = saveChanges;
            
            cancelBtn.onclick = function() {
                const newTaskText = document.createElement('span');
                newTaskText.className = 'task-text';
                newTaskText.textContent = originalText;
                input.replaceWith(newTaskText);
                buttonGroup.innerHTML = originalButtons;
            };
        }

        function completeTask(id) {
            fetch('complete_task.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html> 