<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamsController extends Controller
{
    public function index(){
        return view('admin.teams.index');
    }
    public function getAll(){
        $teams = Team::orderBy('created_at', 'desc');
        return datatables()->of($teams)
            ->addColumn('status', function ($q) {
                $teams = $q->status;
                if ($teams == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.teams.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this teams ?"
                                    data-action=' . route('admin.teams.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->addColumn('image', function ($q) {
                $image=asset('uploads/'.$q->image);
                return '<img src="'.$image.'" width="30" height="30">';
            })
            ->rawColumns(['action', 'status','image'])
            ->toJson();
    }
    public function create(){
        return view('admin.teams.create');
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:teams',
            'status' => 'required|in:active,inactive',
        ]);
        $teams = new Team();
        $teams->name = $request->name;
        $teams->email = $request->email;
        $teams->description = $request->description;
        $teams->status = $request->status;
        $imageName='';
        if($request->hasFile('image')){
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
        }
        $teams->image = $imageName;
        $teams->save();

        return back()->with('success', 'Teams Successfully Created');
    }
    public function edit(Team $team){
        $data['teams']=$team;
        return view('admin.teams.edit',$data);
    }
    public function update(Team $team ,Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:teams,email,'. $team->id,
            'status' => 'required|in:active,inactive',
        ]);
        $team->name = $request->name;
        $team->email = $request->email;
        $team->description = $request->description;
        $team->status = $request->status;
        if($request->hasFile('image')){
            if (isset($team->image) && !empty($team->image)) {
                $oldImagePath = public_path('/uploads') . '/' . $team->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $team->image=$imageName;
            }
        $team->save();

        return back()->with('success', 'Teams Successfully Updated');
    }
    public function destroy(Team $team){
        $team->delete();
        return back()->with('success', 'Teams Successfully Delete');
    }
}
