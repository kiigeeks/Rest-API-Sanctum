<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::all();
        $result = CategoryResource::collection($data);

        return $this->sendResponse($result, 'Successfull Get All Category', 200);
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
                    'name' => 'required|string|max:100'
                ]);

            if ($validator->fails()) {
                return $this->sendError(
                    $validator->errors(),
                    "Validation Error",
                    422
                );
            }

            $data = Category::create($input);

            $result = new CategoryResource($data);

            return $this->sendResponse($result, 'Successfull Add Category', 201);

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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {

        $result = new CategoryResource($category);

        return $this->sendResponse($result, "Successfull Get Detail Category", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        try {
            $input = $request->all();

            $validator = Validator::make(
                $input, [
                    'name' => 'required|string|max:100'
                ]);

            if ($validator->fails()) {
                return $this->sendError(
                    $validator->errors(),
                    "Validation Error",
                    422
                );
            }

            $category->update($input);

            $result = new CategoryResource($category);

            return $this->sendResponse($result, 'Successfull Update Category', 201);

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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $data = Category::findOrFail($category->id);
            $result = new CategoryResource($data);

            Category::destroy($category->id);

            return $this->sendResponse($result, 'Successfull Delete Categories', 201);

        } catch (Exception $error) {
            return $this->sendError(
                $error,
                "Something Wrong",
                400
            );
        }
    }
}
