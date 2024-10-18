<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogList;
use Illuminate\Support\Str;

class BloglistController extends Controller
{
    public function index(){
        return view('admin.blog_management.blog_list.index');
    }
    public function getAll(){
        $bloglist = BlogList::orderBy('created_at', 'desc');
        return datatables()->of($bloglist)
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
                return "<a class='btn btn-sm btn-info' href='" . route('admin.bloglist.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this blog ist?"
                                    data-action=' . route('admin.bloglist.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status','image','description'])
            ->toJson();
    }
    public function create(){
        $data['blogCategorys'] = BlogCategory::where('status','active')->get();
        return view('admin.blog_management.blog_list.create',$data);
    }
    public function store(Request $request){
        $request->validate([
            'blog_category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'blog_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        $bloglist = new BlogList();
        $bloglist->blog_category = $request->blog_category;
        $bloglist->title = $request->title;
        $bloglist->slug = Str::slug($request->title , '-');
        $bloglist->description = $request->description;
        $imageName='';
        if($request->hasFile('blog_image')){
            $file=$request->file('blog_image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
        }
        $bloglist->image = $imageName;
        $bloglist->status = $request->status;
        $bloglist->save();

        return back()->with('success', trans('admin.success_message',['action'=>trans('admin.created'),'title'=>trans('admin.blog').' '.trans('admin.list')]));
    }
    public function edit(BlogList $bloglist){
        $data['blogCategorys'] = BlogCategory::all();
        $data['bloglist']=$bloglist;
        return view('admin.blog_management.blog_list.edit',$data);
    }
    public function update(BlogList $bloglist, Request $request){
        $request->validate([
            'blog_category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'status' => 'required|in:active,inactive'
        ]);
        $bloglist->blog_category = $request->blog_category;
        $bloglist->title = $request->title;
        $bloglist->description = $request->description;
        $bloglist->slug = Str::slug($request->title , '-');
        if($request->hasFile('blog_image')){
            $file=$request->file('blog_image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $bloglist->image = $imageName;
        }
        $bloglist->status = $request->status;
        $bloglist->save();

        return  redirect()->back()->with('success', trans('admin.success_message',['action'=>trans('admin.update'),'title'=>trans('admin.blog').' '.trans('admin.list')]));
    }
    public function destroy(BlogList $bloglist){

        $bloglist->delete();
        return redirect()->back()->with('success', trans('admin.success_message',['action'=>trans('admin.deleted'),'title'=>trans('admin.blog').' '.trans('admin.list')]));

    }
}
