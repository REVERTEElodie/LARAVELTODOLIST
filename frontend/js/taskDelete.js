const TaskDelete = {

	/**
	 * Fonction appelée au click du bouton pour supprimer une tache
	 */
	handleDeleteTask: async function (event) {

		// Boutton clické
		const deleteButton = event.currentTarget

		// li qui contient le bouton sur lequel j'ai clické
		const taskElement = deleteButton.closest('li')

		// Id de la tache a supprimer
		const taskId = taskElement.dataset.id

		// Si j'ai enregistré le nom de la tache dans les dataset
		// const taskName = taskElement.dataset.name

		// Appel de l'API
		// deleteTaskFromApi est une fonction asynchrone
		// Elle retourne une Promesse !
		// Je dois donc attendre la résolution de la promesse avec un await !
		const result = await TaskDelete.deleteTaskFromApi(taskId)

		if (true == result) {
			// Supprimer la tache dans le DOM
			taskElement.remove()
		} else {
			// L'API n'a pas confirmé que la tache est supprimée
			// Ne rien faire ou afficher un message d'erreur pour le visiteur
		}
	},

	/**
	 * Envoie une requete HTTP DELETE vers l'API pour supprimer une tache
	 * La fonction return TRUE si l'API a confirmé que la tache est bien supprimée
	 * La fonction return FALSE sinon.
	 * @param {*} taskId id de la tache à supprimer
	 */
	deleteTaskFromApi: async function (taskId) {

		// try : essaye de faire le code suivant :
		// Dès qu'une erreur se produit dans le try
		// Le code d'arrete aussitot et bascule dans le catch
		try {

			// URL attendue : http://127.0.0.1:8000/api/tasks/<id de la tache à supprimer>
			const url = `${API.endpoint}/${taskId}`

			// Provoque une erreur pour tester : supprimer la tache 42 qui n'existe pas
			// const url = `${API.endpoint}/42`

			// Appel de l'API pour supprimer une tache
			const response = await fetch(url, {
				method: 'DELETE'
			})

			// Est ce que le code de réponse de la reqete n'est PAS de type 2xx (commence par 200)
			if (!response.ok) {
				// L'API a répondu autre chose que 2xx, il s'est produit une erreur
				// J'envoie une erreur
				// Cette erreur sera capturée dans le catch !
				throw new Error('Error reponse not 2xx')
			}

			// Tout s'est bien passé, je return TRUE
			return true;

		} catch (error) { // Une erreur s'est produite dans le code ci dessus
			// J'attrape le message d'erreur avec le catch

			console.error(`Failed to delete task ${error}`)

			// Une erreur s'est produite, je return FALSE
			// Voir le cas par défaut en dernière instruction
			// return false
		}

		// Cas par défaut : return false
		return false
	},
}