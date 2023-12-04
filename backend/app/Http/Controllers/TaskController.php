<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    public function list()
    {
        // Active les logs pour toutes les interractions avec la BDD
        DB::enableQueryLog();

       // ==================================================
       // Pour récupérer les movies avec leurs catégories
		// J'utilise "with" pour demander les films avec leur catégories
		// Je ne peux plus utiliser la fonction all() -> renvoie une erreur
		// Il faut utiliser la fonction get()
		$tasks = Task::with('category')->get();

		Log::info(DB::getQueryLog());
        // =================================================

        // all -> equivalent du find all
        $tasks = Task::all();

        // Log : enregistre une trace de ce qu'il se passe dans mon serveur
        // DB::getQueryLog() -> retourne la dernière requete SQL executée
        // ==> garde une trace de la dernière requete SQL
        // dans le fichier /storage/logs/laravel.log
        Log::debug(DB::getQueryLog());

        // la fonction json(), transforme les
        // objets php en document json
        // c'est un peu l'équivalent de notre fonction
        // show() en saison 6
        return response()->json($tasks);
    }

    public function show($id)
    {

        // Active les logs pour toutes les interractions avec la BDD
        DB::enableQueryLog();

        // all -> equivalent du find all
        $task = Task::find($id);

        // Log : enregistre une trace de ce qu'il se passe dans mon serveur
        // DB::getQueryLog() -> retourne la dernière requete SQL executée
        // ==> garde une trace de la dernière requete SQL
        // dans le fichier /storage/logs/laravel.log
        Log::debug(DB::getQueryLog());

        if (null == $task) {
            // Tache introuvable,
            // retourne le code 404 avec aucune donnée : null
            return response(null, 404);
        }

        // la fonction json(), transforme les
        // objets php en document json
        // c'est un peu l'équivalent de notre fonction
        // show() en saison 6
        return response()->json($task);
    }

    /**
     * Supprime une tache en BDD
     *
     * @param [type] $id identifiant de la tache à supprimer
     * @return void
     */
    public function delete($id)
    {
        // 1. chercher dans la BDD la tache à supprimer
        $task = Task::find($id);

        // 2. Est-ce que la tache demandée (à supprimer) existe ?
        if (null == $task) {
            // la tache demandée n'existe pas => $id ne correspond à aucun id en BDD
            // Arrete le code ici en faisant un return
            // renvoie le code 404 = ressource introuvable
            return response(null, 404);
        }

        // 3. La tache existe bien, je vais pouvoir la supprimer
        if ($task->delete()) {
            // la suppression de la tache s'est bien passée
            // revoie 204
            // https://www.rfc-editor.org/rfc/rfc9110.html#name-delete
            // https://www.rfc-editor.org/rfc/rfc9110.html#status.204
            return response(null, 204);
        } else {
            // suppression échouée
            // erreur interne dans le server
            return response(null, 500);
        }
    }

    /**
     * Ajoute une nouvelle tache dans la BDD
     * Les données sont recues en POST via l'objet $request
     *
     * @param Request $request objet qui représente la requete HTTP POST recue par l'API
     * @return void
     */
    public function create(Request $request)
    {

        // Validation de la requete
        // make prend 2 arguments :
        // 1. les inputs de la requete
        // 2. un tableau de contraintes à respecter sur les inputs
        // tableau associatif clé : nom des input à valider
        // valeur : tableau de contraintes : https://laravel.com/docs/10.x/validation
        // toutes les contraintes possibles sont listées ici : https://laravel.com/docs/10.x/validation#available-validation-rules
        $validator = Validator::make($request->input(), [
            // required => input obligatoire https://laravel.com/docs/10.x/validation#rule-required
            // filled => une valeu pour cet input https://laravel.com/docs/10.x/validation#rule-filled
            'title' => ['required', 'filled'],
            // status n'est pas obligatoir (nullable), mais j'attends une valeur numérique (numeric)
            'status' => ['nullable', 'numeric'],
        ]);

        // Est-ce que les input respectent les contraintes que je viens de déclarer ?
        if ($validator->fails()) {
            // Les inputs sont invalides
            // revois une réponse HTTP avec le code 422
            // https://www.rfc-editor.org/rfc/rfc9110.html#name-422-unprocessable-content
            return response()->json($validator->errors(), 422);
        }

        // créer un nouvel objet tache vide
        $task = new Task();
        $title = $request->input('title');
        $task->title = $title;

        // enregistre dans l'objet $task le status si l'input n'est pas vide
        $status = $request->input('status');
        if (!empty($status)) {
            $task->status = $status;
        }

        if ($task->save()) {
            // enregistrement ok
            // renvoie l'objet $task avec le code HTTP 201
            // https://www.rfc-editor.org/rfc/rfc9110.html#name-post
            // l'id de l'objet $task a été mis à jour par la fonction save()
            return response($task, 201);
        } else {
            // l'enregistrement a échoué => erreur interne du serveur
            // renvoi le code d'erreur 500
            // https://www.rfc-editor.org/rfc/rfc9110.html#name-500-internal-server-error
            return response(null, 500);
        }
    }


    /**
     * Modifie une tache dans la BDD
     * Les données sont recues en PUT via l'objet $request
     *
     * @param Request $request objet qui représente la requete HTTP PUT recue par l'API
     * @param int $id identifiant de la tache à modifier
     * @return void
     */
    public function update(Request $request, $id)
    {

        // Validation de la requete
        // make prend 2 arguments :
        // 1. les inputs de la requete
        // 2. un tableau de contraintes à respecter sur les inputs
        // tableau associatif clé : nom des input à valider
        // valeur : tableau de contraintes : https://laravel.com/docs/10.x/validation
        // toutes les contraintes possibles sont listées ici : https://laravel.com/docs/10.x/validation#available-validation-rules
        $validator = Validator::make($request->input(), [
            // required => input obligatoire https://laravel.com/docs/10.x/validation#rule-required
            // filled => une valeu pour cet input https://laravel.com/docs/10.x/validation#rule-filled
            'title' => ['required', 'filled'],
            // status n'est pas obligatoir (nullable), mais j'attends une valeur numérique (numeric)
            'status' => ['nullable', 'numeric'],
        ]);

        // Est-ce que les input respectent les contraintes que je viens de déclarer ?
        if ($validator->fails()) {
            // Les inputs sont invalides
            // revois une réponse HTTP avec le code 422
            // https://www.rfc-editor.org/rfc/rfc9110.html#name-422-unprocessable-content
            return response()->json($validator->errors(), 422);
        }

        // chercher dans la BDD la tache à modifier
        $task = Task::find($id);

        // Est-ce que la tache à modifier existe bien dans la BDD ?
        if (null == $task) {
            // la tache demandée n'existe pas => $id ne correspond à aucun id en BDD
            // Arrete le code ici en faisant un return
            // renvoie le code 404 = ressource introuvable
            return response(null, 404);
        }

        $title = $request->input('title');
        $task->title = $title;

        // enregistre dans l'objet $task le status si l'input n'est pas vide
        $status = $request->input('status');
        if (!empty($status)) {
            $task->status = $status;
        }

        if ($task->save()) {
            // enregistrement ok
            // renvoie l'objet $task avec le code HTTP 200
            // https://www.rfc-editor.org/rfc/rfc9110.html#name-put
            // l'id de l'objet $task a été mis à jour par la fonction save()
            return response($task, 200);
        } else {
            // l'enregistrement a échoué => erreur interne du serveur
            // renvoi le code d'erreur 500
            // https://www.rfc-editor.org/rfc/rfc9110.html#name-500-internal-server-error
            return response(null, 500);
        }
    }

// **************
// ==== TASKS ====
// **************



      // Pour récupérer les taches avec leurs catégories
        // J'utilise "with" pour demander les taches avec leur catégories
        // Je ne peux plus utiliser la fonction all() -> renvoie une erreur
        // Il faut utiliser la fonction get()
        $tasks = Task::with('category')->get();

  Log::info(DB::getQueryLog());

  $task = Task::with('category')->find($id);

  // Envoyer en réponse la liste des tasks
  return response()->json($tasks);
}

public function show($id)
{
  DB::enableQueryLog();

}
