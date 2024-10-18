<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index()
    {
        return view('customer.keywords.index');
    }

    public function getAll()
    {

        $keywords = auth('customer')->user()->keywords()->select(['id','group_id', 'word', 'customer_phone', 'confirm_message', 'type'])->orderByDesc('created_at');
        return datatables()->of($keywords)
            ->addColumn('type', function(Keyword $q) {
                $type = ucfirst(str_replace('_', '', $q->type));
                return $type;
            })
            ->addColumn('group', function(Keyword $q) {

                return $q->group->name;
            })
            ->addColumn('contacts', function(Keyword $q) {
                $contacts = [];
                foreach ($q->contacts as $keyword_contact) {
                    $contacts[] = trim($keyword_contact->contact->number);
                }
                if($contacts){
                    return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" . implode(", ", $contacts) . "</div>";
                }
                return "";
            })
            ->addColumn('action',function(Keyword $q){
                return "<a class='btn btn-sm btn-info' href='".route('customer.keywords.edit', [$q->id])."'>Edit</a> &nbsp; &nbsp;".
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this keyword?"
                                        data-action='.route('customer.keywords.destroy',[$q]).'
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm">Delete</button>' ;
            })
            ->rawColumns(['contacts','action','type'])
            ->toJson();
    }

    public function create()
    {
        $data['groups']=auth('customer')->user()->groups;
        $data['numbers']= auth('customer')->user()->numbers()->where('is_default','no')->get();
        return view('customer.keywords.create',$data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'word' => 'required',
            'type' => 'required|in:opt_in,opt_out',
        ]);

        $customer_number = auth('customer')->user()->numbers()->where('id', $request->number_id)->firstOrFail();

        $request['customer_phone'] = $customer_number->number;
        $request['customer_number_id'] = $customer_number->number_id;

        if($request->type=='opt_out'){
            $request['group_id']=null;
        }

        auth('customer')->user()->keywords()->create($request->all());
        return back()->with('success', 'keyword successfully created');
    }
    public function edit(Keyword $keyword)
    {
        $data['groups']=auth('customer')->user()->groups;
        $data['keyword'] = $keyword;
        $data['numbers']= auth('customer')->user()->numbers()->where('is_default','no')->get();
        return view('customer.keywords.edit',$data);
    }
    public function update(Keyword $keyword, Request $request)
    {

        $request->validate([
            'word' => 'required',
            'type' => 'required|in:opt_in,opt_out',
        ]);


        $customer_number = auth('customer')->user()->numbers()->where('id', $request->number_id)->firstOrFail();

        $request['customer_phone'] = $customer_number->number;
        $request['customer_number_id'] = $customer_number->number_id;

        if($request->type=='opt_out'){
            $request['group_id']=null;
        }

        $keyword->update($request->all());

        return back()->with('success', 'Keyword successfully updated');
    }
    public function destroy(Keyword $keyword){

        $customer_keyword = auth('customer')->user()->keywords()->where('id',$keyword->id)->first();

        if(!$customer_keyword){
            return redirect()->back()->with('fail','Invalid keyword');
        }
        $keyword->delete();

        return back()->with('success','Keyword successfully deleted');
    }

}
