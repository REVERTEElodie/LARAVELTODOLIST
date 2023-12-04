/**
 * Supprime les taches actuellement dans le DOM
 */
function flushTaskList() {
	const taskList = document.querySelector('.tasklist')
	taskList.innerHTML = ''
}

/**
 * Récupère les taches depuis l'API et les retourne
 */
async function getTaskList() {

	/*
	 * try : essaye de faire le code suivant
	 * si une erreur d'exécution survient dans le try
	 * le code (du try) s'arrete aussitot
	 * on bascule automatiquement dans le code du catch
	 */
	try {
		// 1. récupérer les taches depuis l'API avec un fetch
		const response = await fetch(API.endpoint)

		// 2. transformer la réponse en objet json
		const tasks = await response.json()

		// 3. renvoyer la liste des taches
		return tasks

	} catch (error) {
		// catch attrape toutes les erreurs d'exécution 
		// qui peuvent survenir dans le code du try
		// Par exemple : l'API ne répond plus
		// La variable error contient toutes les informations
		// à propos de l'erreur qui s'est produite durant le try

		console.log(`Erreur durant l'appel de l'API ${error}`)

		// En cas d'erreur, je renvois un tableau vide
		return []
	}
}

async function updateTaskList() {

	// 1. récupérer la liste des taches
	const tasks = await getTaskList()

	const taskList = document.querySelector('.tasklist')

	// 2. Pour chaque tache, l'insérer dans la BDD
	for (const task of tasks) {
		const taskElement = createTaskElement(task)
		taskList.append(taskElement)
	}
}

/**
 * Crée un nouvel élément li qui représente un tache
 * @param {*} task les données d'une tache 
 * @returns un élément du DOM qui représente une tache 
 */
function createTaskElement(task) {

	const taskElement = document.createElement('li')
	// Enregistre dans le dataset id l'identifiant de la tache 
	// Dans la balise li : data-id = <id de la tache>
	taskElement.dataset.id = task.id

	const titleElement = document.createElement('p')
	titleElement.textContent = task.title // titre de la tache passée en argument
	taskElement.append(titleElement)

	taskElement.append(createEditButton())
	taskElement.append(createDeleteButton())

	return taskElement
}

/**
 * Crée un élément du DOM pour un bouton de suppression d'une tache
 */
function createDeleteButton() {
	
	const deleteButton = document.createElement('div')
	deleteButton.classList.add('delete')

	return deleteButton
}

/**
 * Crée un élément du DOM pour un bouton de modification d'une tache
 */
function createEditButton() {

	const editButton = document.createElement('div')
	editButton.classList.add('edit')

	return editButton
}