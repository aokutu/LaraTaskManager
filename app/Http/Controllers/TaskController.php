<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; 

class TaskController extends Controller
{
     public function index()
    {
        $tasks = Task::orderBy('priority', 'asc')->get();
        return view('tasks.index', compact('tasks'));
    }


      public function newtask(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        $highestRank = Task::max('priority') ?? 0;
        Task::create([
            'task' => $request->input('task'),
            'priority' => $highestRank + 1, 
        ]);
      return redirect()->route('tasks.index')->with('success', 'Task added to the end of the list!');
    }

        public function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task removed!');
    }

    public function edit($id)
    {
        $tasks = Task::orderBy('priority', 'asc')->get();
        
        $editingTask = Task::findOrFail($id); 
        
        return view('tasks.index', compact('tasks', 'editingTask'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        $task = Task::findOrFail($id);
        
        $task->update([
            'task' => $request->input('task')
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }


    public function reorder(Request $request)
{
    $order = $request->input('order'); 

    if ($order) {
        foreach ($order as $index => $id) {
            Task::where('id', $id)->update([
                'priority' => $index + 1
            ]);
        }
    }

    return response()->json(['status' => 'success']);
}

}
