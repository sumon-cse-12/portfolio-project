<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventCategory;

class EventCategoryController extends Controller
{
    public function index(){
        return view('admin.event.event_category.index');
    }
    public function getAll(){
        $event_category = EventCategory::orderBy('created_at', 'desc');
        return datatables()->of($event_category)
            ->addColumn('status', function ($q) {
                $event_category = $q->status;
                if ($event_category == 'active') {
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
        return view('admin.event.event_category.create');
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:event_categories,name',
            'status' => 'required|in:active,inactive',
        ]);
        $event_category = new EventCategory();
        $event_category->name = $request->name;
        $event_category->status = $request->status;
        $event_category->save();
        return back()->with('success', trans('admin.success_message',['action'=>trans('admin.created'),'title'=>trans('admin.event').' '.trans('admin.category')]));

    }
}
