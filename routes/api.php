<?php
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Todo;
use App\Exceptions\TodoException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Create a new task
 */
Route::post('todos/create', function(Request $request){
    /**
     * Prevent empty task field
     */
    $validator = Validator::make($request->all(), [
        'task' => 'required'
    ]);

    if($validator->fails()){
        return response()->json([
            "success" => false,
            "message" => "Failed. Please try again",
            "errors" => $validator->errors()
        ],422);
    }

    $data = Todo::create($request->all());
    if(!$data){
        return response()->json([
            "success" => false,
            "message" => "Could not create task. Please try again",
            "errors" => null
        ],500); 
    }
    return response()->json($data, 201);
});

/**
 * Fetch all created tasks
 */
Route::get('/todos', function(){
    return Todo::orderBy('id', 'DESC')->get();
});

/**
 * Update a task
 */
Route::put('/todos/{todo}', function(Request $request, Todo $todo){

    /**
     * Prevent empty task field
     */
    $validator = Validator::make($request->all(), [
        'task' => 'required'
    ]);
    if($validator->fails()){
        return response()->json([
            "success" => false,
            "message" => "Invalid Data",
            "errors" => $validator->errors()
        ],422);
    }

    $data = $todo->update($request->all());
    if(!$data){
        return response()->json(
            [
                "success" => $data,
                "message" => "Could not edit task, please try again.",
                "errors" => null
            ], 
            500);
    }   
    
    //get last updated record
    return Todo::where(['id' => $todo->id])->get();
});

/**
 * Delete a task
 */
Route::delete('todos/delete/{todo}', function(Request $request, Todo $todo){
    $data = $todo->delete();
    if(!$data){
        return response()->json(
            [
                "success" => $data,
                "message" => "Could not delete task, please try again",
                "errors" => null
            ], 
            500);
    }
    return response()->json(
            [
                "success" => $data,
                "message" => "Delete successful",
                "errors" => null
            ], 201);
});

/**
 * Handle wrong routes
 */
Route::fallback(function(){
    return response()->json(['message' => 'Route Not Found.'], 404);
});
