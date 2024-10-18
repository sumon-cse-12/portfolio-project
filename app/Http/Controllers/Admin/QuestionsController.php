<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionsController extends Controller
{
    public function index(){
        return view('admin.questions.index');
    }
    public function getAll(){
        $questions = Question::orderBy('created_at', 'desc');
        return datatables()->of($questions)
            ->addColumn('status', function ($q) {
                $questions = $q->status;
                if ($questions == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.questions.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this question ?"
                                    data-action=' . route('admin.questions.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function create(){
        return view('admin.questions.create');
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $questions = new Question();
        $questions->title = $request->title;
        $questions->description = $request->description;
        $questions->status = $request->status;
        $questions->save();

        return back()->with('success', 'Questions Successfully Created');
    }
    public function edit(Question $question){
        $data['questions']=$question;
        return view('admin.questions.edit',$data);
    }
    public function update(Question $question,Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $question->title = $request->title;
        $question->description = $request->description;
        $question->status = $request->status;
        $question->save();

        return back()->with('success', 'Questions Successfully Updated');
    }
    public function destroy(Question $question){
        $question->delete();
        return back()->with('success', 'Questions Successfully Delete');
    }
}
