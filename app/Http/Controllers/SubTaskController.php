<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTask;

class SubTaskController extends Controller
{
    public function index(){
        return Subtask::all();
    }

    public function store(Request $request){
        $subtask=Subtask::create($request->all());
        return response()->json($subtask,201);
    }
    public function show($id){
        return SubTask::findOrFail($id);
    }
    public function update(Request $request,$id){
        $subtask=SubTask::finOrFail($id);
        $subtask->update($request->all());
        return response()->json($subtask,200);
    }

    public function destroy($id)
    {
        SubTask::destroy($id);
        return response()->json(null,204);
    }
}
