const api = "http://localhost/projects/test_moodle_project/server/api/tasks.php";

async function fetchTasks() {
    const userId = 1;
    const response = await fetch(`${api}?user_id=${userId}`);
    const tasks = await response.json();

    const tasksContainer = document.getElementById("tasks");
    tasksContainer.innerHTML = "";

    tasks.forEach((task) => {
        const tasksElement = document.createElement("div");
        tasksElement.classList.add("task");

        tasksElement.innerHTML = `
        <div>
            <strong>${task.title}</strong>
            <p>${task.description || "No description"}</p>
            <small>End: ${task.end_date}</small>
        </div>
        <button onclick="deleteTask(${task.id})">Delete</button>
        `;

        tasksContainer.appendChild(tasksElement);
    });
}

async function addTask() {
    const title = document.getElementById("taskTitle").value;
    const description = document.getElementById("taskDescription").value;
    const endDate = document.getElementById("taskEndDate").value;

    if (!title || !endDate) {
        alert("Title and end date are required!");
        return;
    }

    const response = await fetch(api, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            user_id: 1,
            title,
            description,
            end_date: endDate,
        }),
    });

    const result = await response.json();
    alert(result.success || result.error);
    fetchTasks();
}

async function deleteTask(taskId) {
    const response = await fetch(api, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: taskId,
            user_id: 1,
        }),
    });

    const result = await response.json();
    alert(result.success || result.error);
    fetchTasks();
}

document.getElementById("addTaskButton").addEventListener("click", addTask);
fetchTasks();