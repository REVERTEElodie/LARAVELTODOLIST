<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;


class TaskController extends Controller

{
public function list() {

    $tasks = Task::all();

    return response()->json($tasks);

}

public function show($id){

    // Active les logs pour toutes les interractions avec la BDD

    DB::enableQueryLog();

    $task = Task::find($id);

   // Log : enregistre une trace de ce qu'il se passe dans mon serveur
   //DB::getQueryLog() -> retourne la derniere requete SQL executée
   //==> garde une trace de la derniere requete SQL
   // dans le fichier /storage/logs/laravel.log

    Log::debug(DB::getQueryLog());

    if(null == $task){
        return response(null, 404);

    return response()->json($task);
}
}

public function update($id){

    // chercher le task a mettre à jour
    $task = Task::find($id);

    //si le task n existe pas en BDD
    // renvoyer 404
    if(null ==$task) {
        return response(null, 404);
    }

    if($task->update()) {
       // operation ok renvoie le code 204
        return response(null, 204);
    } else {
       // operation a echoué, renvoie le code 500 internal error
        return response(null, 500);
    }
}

public function delete($id){

    // chercher le task a supprimer
    $task = Task::find($id);

    //si le task n existe pas en BDD
    // renvoyer 404
    if(null ==$task) {
        return response(null, 404);
    }

    if($task->delete()) {
       // operation ok renvoie le code 204
        return response(null, 204);
    } else {
       // operation a echoué, renvoie le code 500 internal error
        return response(null, 500);
    }
}

//requete HTTP POST
public function create(Request $request) {

// créer un nouvel objet du model task
$task = new Task();

//récupérer les données en POST
$title = $request->input('title');

//modifier l'objet task avec le title de POST
$task->title = $title;

//enregistre en BDD
if($task->save()) {

// enregistrement ok renvoi le code 201
return response($task, 201);
} else {

// enregistrement a echoué
// renvoi le code erreur 500
return response(null, 500);
}

}
}
// active les logs de la BDD
DB::enableQueryLog();


