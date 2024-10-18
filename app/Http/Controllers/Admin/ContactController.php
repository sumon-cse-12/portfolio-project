<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(){
        return view('admin.contact.index');
    }
    public function getAll(){
        $contacts = Contact::orderBy('created_at', 'desc');
        return datatables()->of($contacts)
            ->addColumn('status', function ($q) {
                $contacts = $q->status;
                if ($contacts == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.contact.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this question ?"
                                    data-action=' . route('admin.contact.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function create(){
        $data['contacts'] = Contact::first();
        return view('admin.contact.create',$data);
    }
    public function store(Request $request){
        $request->validate([
            'header_title' => 'required',
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $contacts = Contact::firstOrNew();
        $contacts->header_title = $request->header_title;
        $contacts->title = $request->title;
        $contacts->short_description = $request->short_description;
        $contacts->description = $request->description;
        $contacts->status = $request->status;
        if ($request->has('features_title') && $request->has('features_data')) {
            $features_title = array_filter($request->features_title);
            $features_data = array_filter($request->features_data);
            $features = [];
            foreach ($features_title as $index => $head) {
                if (isset($features_data[$index])) {
                    $features[] = [
                        'features_title' => $head,
                        'features_data' => $features_data[$index]
                    ];
                }
            }
            $contacts->features = json_encode($features);
        }
        $contacts->save();

        return back()->with('success', 'Contacts Successfully Created');
    }
    public function edit(Contact $contact){
        $data['contacts'] = $contact;
        return view('admin.contact.edit',$data);
    }
    public function update(Contact $contact,Request $request){
        $request->validate([
            'header_title' => 'required',
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $contact->header_title = $request->header_title;
        $contact->title = $request->title;
        $contact->short_description = $request->short_description;
        $contact->status = $request->status;
        if ($request->has('features_title') && $request->has('features_data')) {
            $features_title = array_filter($request->features_title);
            $features_data = array_filter($request->features_data);
            $features = [];
            foreach ($features_title as $index => $head) {
                if (isset($features_data[$index])) {
                    $features[] = [
                        'features_title' => $head,
                        'features_data' => $features_data[$index]
                    ];
                }
            }
            $contact->features = json_encode($features);
        }
        $contact->save();

        return back()->with('success', 'Contacts Successfully Updated');
    }
}
