const API = {
	endpoint: 'http://127.0.0.1:8000/api/tasks'
}

const App = {
	
	init: function () {
		TaskList.flushTaskList()
	
		TaskList.updateTaskList()

		TaskPost.init()
	},
}


document.addEventListener('DOMContentLoaded', App.init)