<?php
/**
 * This class is deprecated
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;


class TodoController extends Controller
{
    /**
     * Add a new item to the todo list
     */
    public function create(Request $request){
        $todo = Todo::create($request->all());
        return response()->json($todo, 201);
    }

    /**
     * Display all tasks in the todo list
     */
    public function index(){
        return Todo::orderBy('id', 'DESC')->get();
    }

    /**
     * Update a task on the todo list
     */
    public function update(Request $request, Todo $todo){
        $todo->update($request->all());
        return response()->json($todo, 200);
    }

    /**
     * Delete a task on the todo list
     */
    public function delete(Todo $todo){
        $todo->delete();
        return response()->json(null, 204);
    }
}
