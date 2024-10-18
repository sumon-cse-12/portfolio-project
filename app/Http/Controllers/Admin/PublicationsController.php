<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryPublication;
use App\Models\Publication;
use App\Models\PublicationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicationsController extends Controller
{
    public function index(){
        return view('admin.publication.index');
    }
    public function getAll(){
        $publications = Publication::orderBy('created_at', 'desc');
        return datatables()->of($publications)
            ->addColumn('status', function ($q) {
                $status = $q->status;
                if ($status == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('image', function ($q) {
                $image=asset('uploads/'.$q->image);

                return '<img src="'.$image.'" width="30" height="30">';
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.publications.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this publications?"
                                    data-action=' . route('admin.publications.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status','image','description'])
            ->toJson();
    }
    public function create(){
        $data['category_publications'] = CategoryPublication::where('status','active')->get();
        return view('admin.publication.create',$data);
    }
    public function store(Request $request){
        $request->validate([
            'publication_category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'publication_image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $publication = new Publication();
        $publication->publication_category = $request->publication_category;
        $publication->title = $request->title;
        $publication->slug = Str::slug($request->title , '-');
        $publication->description = $request->description;
        if($request->hasFile('publication_image')){
            $file=$request->file('publication_image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $publication->image = $imageName;
        }
        $publication->image = $imageName;
        $publication->status = $request->status;
        $publication->save();
        return back()->with('success', 'Publications successfully created');
    }
    public function edit(Publication $publication){
        $data['category_publications'] = CategoryPublication::where('status','active')->get();
        $data['publication']=$publication;
        return view('admin.publication.edit',$data);
    }
    public function update(Publication $publication, Request $request){
        $request->validate([
            'publication_category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $publication->publication_category = $request->publication_category;
        $publication->description = $request->description;
        $publication->slug = Str::slug($request->title , '-');
        $publication->title = $request->title;
        if($request->hasFile('publication_image')){
            $file=$request->file('publication_image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $publication->image = $imageName;
        }
        $publication->status = $request->status;
        $publication->save();
        return back()->with('success', 'Publications successfully updated');
    }
    public function destroy(Publication $publication){

        $publication->delete();
        return back()->with('success', 'Publications successfully deleted');
    }
}
