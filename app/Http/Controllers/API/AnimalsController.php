<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Animals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AnimalsResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnimalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Animals::all();
        $result = AnimalsResource::collection($data);

        return $this->sendResponse($result, 'Successfull Get All Animals', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make(
                $input, [
                    'name' => 'required|string|max:100',
                    'gender' => 'required|string',
                    'food' => 'required|string',
                    'description' => 'required',
                    'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|file|max:1024',
                    'categories_id' => 'required'
                ]);

            if ($validator->fails()) {
                return $this->sendError(
                    $validator->errors(),
                    "Validation Error",
                    422
                );
            }

            if($request->file('image')){
                $input['image'] = $request->file('image')->store('Images');
            }

            $input['users_id'] = Auth::user()->id;

            $data = Animals::create($input);
            $result = new AnimalsResource($data);

            return $this->sendResponse(
                $result,
                "Success Add Animals",
                201
            );

        } catch (Exception $error) {
            return $this->sendError(
                $error,
                "Something Wrong",
                400
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Animals  $animals
     * @return \Illuminate\Http\Response
     */
    public function show(Animals $animal)
    {

        $result = new AnimalsResource($animal);

        return $this->sendResponse($result, "Successfull Get Detail Animals", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Animals  $animals
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animals $animal)
    {
        try {
            $input = $request->all();
            $validator = Validator::make(
                $input, [
                    'name' => 'required|string|max:100',
                    'gender' => 'required|string',
                    'food' => 'required|string',
                    'description' => 'required',
                    'image' => 'image|mimes:jpg,png,jpeg,gif,svg,webp|file|max:1024',
                    'categories_id' => 'required'
                ]);

            if ($validator->fails()) {
                return $this->sendError(
                    $validator->errors(),
                    "Validation Error",
                    422
                );
            }

            if($request->file('image')){
                if($animal->image) {
                    Storage::delete($animal->image);
                }
                $input['image'] = $request->file('image')->store('Images');
            }

            $animal->update($input);
            $result = new AnimalsResource($animal);

            return $this->sendResponse(
                $result,
                "Success Update Animals",
                201
            );

        } catch (Exception $error) {
            return $this->sendError(
                $error,
                "Something Wrong",
                400
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Animals  $animals
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animals $animal)
    {
        try {
            $data = Animals::findOrFail($animal->id);
            $result = new AnimalsResource($data);

            if ($animal->image){
                Storage::delete($animal->image);
            }

            Animals::destroy($animal->id);

            return $this->sendResponse($result, 'Successfull Delete Animals', 201);

        } catch (Exception $error) {
            return $this->sendError(
                $error,
                "Something Wrong",
                400
            );
        }
    }
}
