<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CoursesController extends Controller
{
    public function index(){
        return view('admin.courses.index');
    }
    public function getAll(){
        $courses = Course::orderBy('created_at', 'desc');
        return datatables()->of($courses)
            ->addColumn('status', function ($q) {
                $courses = $q->status;
                if ($courses == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.courses.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this course ?"
                                    data-action=' . route('admin.courses.destroy', [$q]) . '
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
        return view('admin.courses.create');
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $courses = new Course();
        $courses->title = $request->title;
        $courses->description = $request->description;
        $courses->status = $request->status;
        $imageName='';
        if($request->hasFile('image')){
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
        }
        $courses->image = $imageName;
        $courses->more_details = $request->more_details;
        $courses->save();

        return back()->with('success', 'Courses Successfully Created');
    }
    public function edit(Course $course){
        $data['courses']=$course;
        return view('admin.courses.edit',$data);
    }
    public function update(Course $course ,Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $course->title = $request->title;
        $course->description = $request->description;
        $course->status = $request->status;
        if($request->hasFile('image')){
            if (isset($course->image) && !empty($course->image)) {
                $oldImagePath = public_path('/uploads') . '/' . $course->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $course->image=$imageName;
            }
        $course->more_details = $request->more_details;
        $course->save();

        return back()->with('success', 'Courses Successfully Updated');
    }
    public function destroy(Course $course){
        $course->delete();
        return back()->with('success', 'Courses Successfully Delete');
    }
}
