<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        return view('customer.label.index');
    }

    public function getAll()
    {

        $keywords = auth('customer')->user()->labels()->select(['id','title','color', 'status']);
        return datatables()->of($keywords)
            ->addColumn('title',function ($q){
                return ucfirst($q->title);
            })
            ->addColumn('color', function ($q){

                return "<span style='padding: 0px 20px; background: $q->color'></span>";
            })
            ->addColumn('status',function($q){
                if($q->status=='active') {
                    $status= '<strong class="text-white bg-success px-2 py-1 rounded status-font-size"> '.ucfirst($q->status).' </strong>';
                }else{
                    $status= '<strong class="text-white bg-danger px-2 py-1 rounded status-font-size"> '.ucfirst($q->status).' </strong>';
                }
                return $status;
            })
            ->addColumn('action',function(Label $q){
                return "<a class='btn btn-sm btn-info' href='".route('customer.label.edit', [$q->id])."' title='Edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; &nbsp;".
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this label?"
                                        data-action='.route('customer.label.destroy',[$q]).'
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>' ;
            })
            ->rawColumns(['color','action','status'])
            ->toJson();
    }

    public function create()
    {

        return view('customer.label.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $preLabel = auth('customer')->user()->labels()->where('title', $request->title)->first();
        if ($preLabel){
            return redirect()->back()->withErrors(['failed'=>'Already have this label, try another']);
        }

        auth('customer')->user()->labels()->create($request->only('title','status','color'));

        return redirect()->route('customer.label.index')->with('success', 'Label successfully created');
    }
    public function edit(Label $label)
    {
        $data['label'] = $label;
        return view('customer.label.edit',$data);
    }
    public function update(Label $label, Request $request)
    {
        if ($label->title == 'new') {
            $label->update($request->only('status', 'color'));
        } else {
            $request->validate([
                'title' => 'required',
                'status' => 'required|in:active,inactive',
            ]);
            $label->update($request->all());
        }

        return redirect()->route('customer.label.index')->with('success', 'Label successfully updated');
    }
    public function destroy(Label $label){

        if ($label->title=='new'){
            return redirect()->back()->withErrors(['failed'=>'You can not delete this label']);
        }
        $customer = auth('customer')->user();

        if($label->customer_id != $customer->id){
            return abort(404);
        }
        $label->delete();

        return back()->with('success','Label successfully deleted');
    }
}
