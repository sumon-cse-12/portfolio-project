<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resource;

class ResourcesController extends Controller
{
    public function index(){
        return view('admin.resources.index');
    }
    public function getAll(){
        $resources = Resource::orderBy('created_at', 'desc');
        return datatables()->of($resources)
            ->addColumn('status', function ($q) {
                $resources = $q->status;
                if ($resources == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.resources.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this resources ?"
                                    data-action=' . route('admin.resources.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function create(){
        $data['resources']=Resource::first();
        return view('admin.resources.create',$data);
    }
    public function store(Request $request){
        $request->validate([
            'header_title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $resources = Resource::firstOrNew();
        $resources->header_title = $request->header_title;
        $resources->status = $request->status;
        $resources->description = $request->description;
        $resources->save();

        return back()->with('success', 'Resources Successfully Created');
    }
    public function edit(Resource $resource){
        $data['resources']=$resource;
        return view('admin.resources.edit',$data);
    }
    public function update(resource $resource ,Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $resource->title = $request->title;
        $resource->sub_title = $request->sub_title;
        $resource->status = $request->status;
        $resource->description = $request->description;
        $resource->save();

        return back()->with('success', 'Resources Successfully Updated');
    }
    public function destroy(resource $resource){
        $resource->delete();
        return back()->with('success', 'Resources Successfully Delete');
    }
}
