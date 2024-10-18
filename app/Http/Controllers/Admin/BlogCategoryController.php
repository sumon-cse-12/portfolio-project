<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    public function index(){
        return view('admin.blog_management.blog_category.index');
    }
        public function getAll(){
        $blogcategory = BlogCategory::orderBy('created_at', 'desc');
        return datatables()->of($blogcategory)
            ->addColumn('status', function ($q) {
                $blogcategory = $q->status;
                if ($blogcategory == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.blog-category.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this blog category?"
                                    data-action=' . route('admin.blog-category.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function create(){
        return view('admin.blog_management.blog_category.create');
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:blog_categories,name',
            'status' => 'required|in:active,inactive',
        ]);
        $blogcategory = new BlogCategory();
        $blogcategory->name = $request->name;
        $blogcategory->status = $request->status;
        $blogcategory->save();
        return back()->with('success', trans('admin.success_message',['action'=>trans('admin.created'),'title'=>trans('admin.blog').' '.trans('admin.category')]));

    }
    public function edit(BlogCategory $blog_category)
    {
        $data['blog_category'] = $blog_category;
        return view('admin.blog_management.blog_category.edit',$data);
    }
    public function update(BlogCategory $blog_category, Request $request){

        $request->validate([
            'name' => 'required|unique:blog_categories,name,' . $blog_category->id,
            'status' => 'required|in:active,inactive',
        ]);

        $blog_category->name = $request->name;
        $blog_category->status = $request->status;
        $$blog_category->save();
        return redirect()->back()->with('success', trans('admin.success_message',['action'=>trans('admin.update'),'title'=>trans('admin.blog').' '.trans('admin.category')]));
    }
    public function destroy(BlogCategory $blog_category){

        $blog_category->delete();
        return redirect()->back()->with('success', trans('admin.success_message',['action'=>trans('admin.deleted'),'title'=>trans('admin.blog').' '.trans('admin.category')]));

    }
}
