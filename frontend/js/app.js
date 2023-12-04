const API = {
	endpoint: 'http://127.0.0.1:8000/api/tasks'
}

async function init() {
	flushTaskList()

	updateTaskList()

	
}

document.addEventListener('DOMContentLoaded', init)