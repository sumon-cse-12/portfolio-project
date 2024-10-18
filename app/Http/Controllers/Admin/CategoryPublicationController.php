<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryPublication;
use Illuminate\Http\Request;

class CategoryPublicationController extends Controller
{
    public function index(){
        return view('admin.publication_category.index');
    }
        public function getAll(){
        $category_publication = CategoryPublication::orderBy('created_at', 'desc');
        return datatables()->of($category_publication)
            ->addColumn('status', function ($q) {
                $category_publication = $q->status;
                if ($category_publication == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.category-publication.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this publication category?"
                                    data-action=' . route('admin.category-publication.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function create(){
        return view('admin.publication_category.create');
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:category_publications,name',
            'status' => 'required|in:active,inactive',
        ]);
        $category_publication = new CategoryPublication();
        $category_publication->name = $request->name;
        $category_publication->status = $request->status;
        $category_publication->save();
        return back()->with('success', trans('admin.publication_category_created'));


    }
    public function edit(CategoryPublication $category_publication)
    {
        $data['category_publication'] = $category_publication;
        return view('admin.publication_category.edit',$data);
    }
    public function update(CategoryPublication $category_publication, Request $request){

        $request->validate([
            'name' => 'required|unique:category_publications,name,' . $category_publication->id,
            'status' => 'required|in:active,inactive',
        ]);

        $category_publication->name = $request->name;
        $category_publication->status = $request->status;
        $category_publication->save();
        return redirect()->back()->with('success', trans('admin.success_message',['action'=>trans('admin.update'),'title'=>trans('admin.publication').' '.trans('admin.category')]));
    }
    public function destroy(CategoryPublication $category_publication){

        $category_publication->delete();
        return redirect()->back()->with('success', trans('admin.success_message',['action'=>trans('admin.deleted'),'title'=>trans('admin.publication').' '.trans('admin.category')]));

    }
}
