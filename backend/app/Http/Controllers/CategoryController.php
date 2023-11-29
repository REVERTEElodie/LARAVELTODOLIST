<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;


class CategoryController extends Controller

{
public function list() {

    $categories = Category::all();

    return response()->json($categories);

}

public function show($id){

    // Active les logs pour toutes les interractions avec la BDD

    DB::enableQueryLog();

    $category = Category::find($id);

   // Log : enregistre une trace de ce qu'il se passe dans mon serveur
   //DB::getQueryLog() -> retourne la derniere requete SQL executée
   //==> garde une trace de la derniere requete SQL
   // dans le fichier /storage/logs/laravel.log

    Log::debug(DB::getQueryLog());

    if(null == $category){
        return response(null, 404);

    return response()->json($category);
}
}


public function delete($id){

    // chercher le category a supprimer
    $category = Category::find($id);

    //si le category n existe pas en BDD
    // renvoyer 404
    if(null ==$category) {
        return response(null, 404);
    }

    if($category->delete()) {
       // operation ok renvoie le code 204
        return response(null, 204);
    } else {
       // operation a echoué, renvoie le code 500 internal error
        return response(null, 500);
    }
}


public function update($id){

    // chercher le category a mettre à jour
    $category = Category::find($id);

    //si le category n existe pas en BDD
    // renvoyer 404
    if(null ==$category) {
        return response(null, 404);
    }

    if($category->update()) {
       // operation ok renvoie le code 204
        return response(null, 204);
    } else {
       // operation a echoué, renvoie le code 500 internal error
        return response(null, 500);
    }
}


//requete HTTP POST
public function create(Request $request) {

// créer un nouvel objet du model category
$category = new Category();

//récupérer les données en POST
$title = $request->input('title');

//modifier l'objet category avec le title de POST
$category->title = $title;

//enregistre en BDD
if($category->save()) {

// enregistrement ok renvoi le code 201
return response($category, 201);
} else {

// enregistrement a echoué
// renvoi le code erreur 500
return response(null, 500);
}

}
}
// active les logs de la BDD
DB::enableQueryLog();
