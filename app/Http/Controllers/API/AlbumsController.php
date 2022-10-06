<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Albums;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AlbumsResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AlbumsDetailResource;

class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Albums::all();
        $result = AlbumsResource::collection($data);

        return $this->sendResponse($result, 'Success Get All Albums', 200);
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
                    'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|file|max:1024',
                    'animals_id' => 'required'
                ]);

            if ($validator->fails()) {
                return $this->sendError(
                    $validator->errors(),
                    "Validation Error",
                    422
                );
            }

            if($request->file('image')){
                $input['image'] = $request->file('image')->store('Albums');
            }

            $input['users_id'] = Auth::user()->id;

            $data = Albums::create($input);
            $result = new AlbumsDetailResource($data);

            return $this->sendResponse(
                $result,
                "Success Add Albums",
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
     * @param  \App\Models\Albums  $albums
     * @return \Illuminate\Http\Response
     */
    public function show(Albums $album)
    {
        $result = new AlbumsDetailResource($album);

        return $this->sendResponse($result, "Successfull Get Detail Albums", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Albums  $albums
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Albums $album)
    {
        try {
            $input = $request->all();

            $validator = Validator::make(
                $input, [
                    'image' => 'image|mimes:jpg,png,jpeg,gif,svg,webp|file|max:1024'
                ]);

            if ($validator->fails()) {
                return $this->sendError(
                    $validator->errors(),
                    "Validation Error",
                    422
                );
            }

            if($request->file('image')){
                if($album->image) {
                    Storage::delete($album->image);
                }
                $input['image'] = $request->file('image')->store('Albums');
            }

            $album->update($input);
            $result = new AlbumsDetailResource($album);

            return $this->sendResponse(
                $result,
                "Success Update Albums",
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
     * @param  \App\Models\Albums  $albums
     * @return \Illuminate\Http\Response
     */
    public function destroy(Albums $album )
    {
        try {
            $data = Albums::findOrFail($album->id);
            $result = new AlbumsDetailResource($data);

            if ($album->image){
                Storage::delete($album->image);
            }

            Albums::destroy($album->id);

            return $this->sendResponse($result, 'Successfull Delete Albums', 201);

        } catch (Exception $error) {
            return $this->sendError(
                $error,
                "Something Wrong",
                400
            );
        }
    }
}
