<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Controllers\Controller;


class TagController extends Controller

{
public function list() {

    $tags = Tag::all();

    return response()->json($tags);

}

public function show($id){

    // Active les logs pour toutes les interractions avec la BDD

    DB::enableQueryLog();

    $tag = Tag::find($id);

   // Log : enregistre une trace de ce qu'il se passe dans mon serveur
   //DB::getQueryLog() -> retourne la derniere requete SQL executée
   //==> garde une trace de la derniere requete SQL
   // dans le fichier /storage/logs/laravel.log

    Log::debug(DB::getQueryLog());

    if(null == $tag){
        return response(null, 404);

    return response()->json($tag);
}
}

public function update($id){

    // chercher le tag a mettre à jour
    $tag = Tag::find($id);

    //si le tag n existe pas en BDD
    // renvoyer 404
    if(null ==$tag) {
        return response(null, 404);
    }

    if($tag->update()) {
       // operation ok renvoie le code 204
        return response(null, 204);
    } else {
       // operation a echoué, renvoie le code 500 internal error
        return response(null, 500);
    }
}

public function delete($id){

    // chercher le tag a supprimer
    $tag = Tag::find($id);

    //si le tag n existe pas en BDD
    // renvoyer 404
    if(null ==$tag) {
        return response(null, 404);
    }

    if($tag->delete()) {
       // operation ok renvoie le code 204
        return response(null, 204);
    } else {
       // operation a echoué, renvoie le code 500 internal error
        return response(null, 500);
    }
}





//requete HTTP POST
public function create(Request $request) {

// créer un nouvel objet du model tag
$tag = new Tag();

//récupérer les données en POST
$title = $request->input('title');

//modifier l'objet tag avec le title de POST
$tag->title = $title;

//enregistre en BDD
if($tag->save()) {

// enregistrement ok renvoi le code 201
return response($tag, 201);
} else {

// enregistrement a echoué
// renvoi le code erreur 500
return response(null, 500);
}

}
}
// active les logs de la BDD
DB::enableQueryLog();
