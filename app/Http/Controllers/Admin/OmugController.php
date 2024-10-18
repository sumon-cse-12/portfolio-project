<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Omug;

class OmugController extends Controller
{
    public function index(){
        return view('admin.omug.index');
    }
    public function create(){
        $data['omug']=Omug::first();
        return view('admin.omug.create',$data);
    }
    public function getAll(){
        $omug = Omug::orderBy('created_at', 'desc');
        return datatables()->of($omug)
            ->addColumn('status', function ($q) {
                $omug = $q->status;
                if ($omug == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.omug.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this OMUG ?"
                                    data-action=' . route('admin.omug.destroy', [$q]) . '
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
    public function store(Request $request){
        dd($request->all());
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $omug =  Omug::firstOrNew();
        $omug->title = $request->title;
        $omug->short_description = $request->short_description;
        $omug->description = $request->description;
        $omug->video_link = $request->video_link;
        $omug->image_link = $request->image_link;
        $imageName='';
        if($request->hasFile('image')){
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
        }
        $omug->image = $imageName;
        $omug->status = $request->status;
        $omug->save();

        return back()->with('success', 'OMUG Successfully Created');
    }
    public function edit(Omug $omug){
        $data['omug']=$omug;
        return view('admin.omug.edit',$data);
    }
    public function update(Request $request,Omug $omug){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $omug->title = $request->title;
        $omug->sub_title = $request->sub_title;
        $omug->description = $request->description;
        $omug->video_link = $request->video_link;
        $omug->image_link = $request->image_link;
        if($request->hasFile('image')){
            if (isset($omug->image) && !empty($omug->image)) {
                $oldImagePath = public_path('/uploads') . '/' . $omug->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $omug->image=$imageName;
        }
        $omug->status = $request->status;
        $omug->save();

        return back()->with('success', 'OMUG Successfully Updated');
    }
    public function destroy(Omug $omug){

        $omug->delete();
        return back()->with('success', 'OMUG Successfully Delete');
    }
}
