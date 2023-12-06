<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

	public function list()
	{
		DB::enableQueryLog();

        // Récupérer toutes les catégories et leurs taches
		$categories = Category::with('tasks')->get();

		Log::info(DB::getQueryLog());

		return response()->json($categories);
	}

	public function show($id)
	{
		DB::enableQueryLog();

        // Récupérer une catégorie et ses taches associées
		$category = Category::with('tasks')->find($id);

		Log::info(DB::getQueryLog());

		if (null == $category) {
			return response(null, 404);
		}

		return response()->json($category);
	}

	public function delete($id)
	{
		$category = Category::find($id);

		if (null == $category) {
			// 404 ressource not found
			return response(null, 404);
		}

		if ($category->delete()) {
			// 204 delete ok, response without content
			return response(null, 204);
		} else {
			// 500 internal server error
			return response(null, 500);
		}
	}

	public function create(Request $request)
	{
		// Validation des donnée en input
		// https://laravel.com/docs/10.x/validation
		$validator = Validator::make($request->input(), [
			'name' => ['required', 'filled'],
		]);

		// En cas d'échec de validation des input
		if ($validator->fails()) {
			// 422 input data are incorrect
			return response()->json($validator->errors(), 422);
		}

		$name = $request->input('name');

		$category = new Category();
		$category->name = $name;

		if($category->save()) {
			// 201 creation ok
			return response($category, 201);
		} else {
			// 500 internal error
			return response(null, 500);
		}
	}

	public function update(Request $request, $id)
	{
		// Validation des donnée en input
		// https://laravel.com/docs/10.x/validation
		$validator = Validator::make($request->input(), [
			'name' => ['required', 'filled'],
		]);

		// En cas d'échec de validation des input
		if ($validator->fails()) {
			// 422 input data are incorrect
			return response()->json($validator->errors(), 422);
		}
		$category = Category::find($id);
		if (null === $category) {
			// ressource not found
			return response(null, 404);
		}

		$name = $request->input('name');
		$category->name = $name;

		if($category->save()) {
			// 201 creation ok
			return response($category, 201);
		} else {
			// 500 internal error
			return response(null, 500);
		}
	}
}
