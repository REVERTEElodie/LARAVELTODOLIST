const TaskPost = {

	// Variable globale dans le module
	dialogBox: null,

	init: function () {
		// Initialise la variable globale du module
		// Avec l'élément du DOM ".modal-dialog" => la div qui contient le formulaire
		TaskPost.dialogBox = document.querySelector('.modal-dialog')

		// Bouton pour afficher la dialog box avec le formulaire d'ajout d'une tache
		const newTaskButton = document.querySelector('.create-task-container button')
		newTaskButton.addEventListener('click', TaskPost.handleDisplayDialogBox)

		// Bouton pour valider le formulaire
		postNewTaskButton = TaskPost.dialogBox.querySelector('button')
		postNewTaskButton.addEventListener('click', (event) => {
			TaskPost.handlePostNewTask(event)
		})

	},

	handlePostNewTask: async function (event) {

		// Prevent default behavior: refresh page
		// Dire au navigateur : fait rien quand je valide le formulaire
		// C'est mon script JS qui va tout gérer ;) 
		event.preventDefault();

		// 1. Récupérer les données du formulaire

		// La balise form dans le DOM
		const formElement = TaskPost.dialogBox.querySelector('form')
		// Toutes les données du forumaire
		const data = new FormData(formElement)

		// TEST : vérifier que j'ai bien les données du forumaire
		// Affiche le title saisi dans le formulaire
		// title est le "name" de l'input dans le form
		// console.log(`Titre dans le formualire : ${data.get('title')}`)

		// 2. Envoyer les données du formulaire à l'API
		const result = await TaskPost.postNewTaskToApi(data)

		// Vérifie le résultat du fetech
		if (false == result) {
			// 3. Bonus : gérer le cas d'erreur : la tache n'a pas été enregistrée
			console.error(`Jai pas réussi à enregistrer la nouvelle tache :(`)
		} else {
			// 3 Gérer le cas nominal : la tache a bien été enregistrée
			// Mettre à jour la liste des tache dans le DOM
			// result contient ma nouvelle tache avec sa catégorie
			TaskPost.insertNewTaskIntoList(result)
		}

		// Cacher la dialog box
		console.log(`Jai clicqué sur ajouter une nouvelle tache`)
		TaskPost.toggleDialogBox()
	},

	/**
	 * Ajoute une nouvelle tache dans le DOM
	 * La fonction réutilise createTaskElement du module TaskList
	 * @param {*} task un objet JSON qui contient toutes les infos de la tache + sa catégorie
	 */
	insertNewTaskIntoList: function (task) {
		// La liste des taches dans le DOM
		const taskListElement = document.querySelector('.tasklist')

		const taskElement = TaskList.createTaskElement(task)
		taskListElement.append(taskElement)
	},

	/**
	 * Envoie une requete HTTTP POST à l'API pour créer une nouvelle tache
	 * @param {*} data les données du formualire avec toutes les infos de la nouvelle tache
	 * data contient toutes les données du formulaire au format clé / valeur
	 * 
	 * La fonction retour FALSE si une erreur est survenue pendant l'enregistrement de la tache
	 */
	postNewTaskToApi: async function (dataForm) {
		try {
			const response = await fetch(API.endpoint, {
				method: 'POST',
				body: dataForm
			})

			// Si l'API m'a bien réponde, mais avec un code qui n'est pas 2xx
			// Il s'est passé une erreur 
			if (!response.ok) {
				throw new Error(`Impossible d'enregistrer la nouvelle tache`)
			}

			// Tout s'est bien passé
			// Je récupère le body de la réponse de l'API
			// Pour renvoyer toutes les données de la nouvelle tache
			const newTask = await response.json()

			return newTask

		} catch (error) {
			console.error(`Failed to save new task ${error}`)
		}

		// Cas d'erreur par défaut 
		// retourne FALSE
		return false
	},

	/**
	 * Fonction appelée au moment du click sur le bouton "nouvelle tache"
	 */
	handleDisplayDialogBox: function () {
		// Afficher la dialog box
		TaskPost.toggleDialogBox()
	},

	/**
	 * Affiche la dialog box si elle est cachée
	 * Cache la dialog box si elle est affichée
	 * https://developer.mozilla.org/fr/docs/Web/API/Element/classList#toggle
	 */
	toggleDialogBox: function () {
		TaskPost.dialogBox.classList.toggle('show') // Ajoute ou supprime le classe show pour la dialog box
	},
}