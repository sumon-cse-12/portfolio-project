<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServicesController extends Controller
{
    public function index(){
        return view('admin.services.index');
    }
    public function getAll(){
        $services = Service::orderBy('created_at', 'desc');
        return datatables()->of($services)
            ->addColumn('status', function ($q) {
                $services = $q->status;
                if ($services == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.services.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this service ?"
                                    data-action=' . route('admin.services.destroy', [$q]) . '
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
        return view('admin.services.create');
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $services = new Service();
        $services->title = $request->title;
        $imageName='';
        if($request->hasFile('image')){
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
        }
        $services->image = $imageName;
        $services->status = $request->status;
        if ($request->has('features')) {
            $features = array_filter($request->features);
    
            $services->features = json_encode($features);
        }
        $services->save();

        return back()->with('success', 'Services Successfully Created');
    }
    public function edit(Service $service){
        $data['services']=$service;
        return view('admin.services.edit',$data);
    }
    public function update(Service $service , Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $service->title = $request->title;
        $service->status = $request->status;
        if($request->hasFile('image')){
            if (isset($service->image) && !empty($service->image)) {
                $oldImagePath = public_path('/uploads') . '/' . $service->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $service->image=$imageName;
        }
            if ($request->has('features')) {
                $features = array_filter($request->features);
        
                $service->features = json_encode($features);
        }
        $service->save();

        return back()->with('success', 'Services Successfully Updated');
    }
    public function destroy(Service $service){

        $service->delete();
        return back()->with('success', 'Services Successfully Delete');
    }
    public function header(){
        return view('admin.services.header');
    }
}
