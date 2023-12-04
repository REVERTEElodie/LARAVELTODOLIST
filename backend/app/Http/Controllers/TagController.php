<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function list()
	{
		return response()->json(Tag::all());
	}

	public function show($id)
	{
		$movie = Tag::find($id);

		if (null == $movie) {
			return response(null, 404);
		}

		return response()->json($movie);
	}

	public function delete($id)
	{
		$tag = Tag::find($id);

		if (null == $tag) {
			// 404 ressource not found
			return response(null, 404);
		}

		if ($tag->delete()) {
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

		$tag = new Tag();
		$tag->name = $name;

		if ($tag->save()) {
			// 201 creation ok
			return response($tag, 201);
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

		$tag = Tag::find($id);
		if (null === $tag) {
			// ressource not found
			return response(null, 404);
		}

		$name = $request->input('name');
		$tag->name = $name;

		if ($tag->save()) {
			// 201 creation ok
			return response($tag, 200);
		} else {
			// 500 internal error
			return response(null, 500);
		}
	}
}
