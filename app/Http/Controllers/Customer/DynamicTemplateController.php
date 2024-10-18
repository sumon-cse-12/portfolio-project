<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DynamicTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DynamicTemplateController extends Controller
{
    public function index(){

        return view('customer.dynamic_template.index');
    }

    public function getAll()
    {
        $template=DynamicTemplate::where('customer_id', auth('customer')->user()->id)->get();
        return datatables()->of($template)

            ->addColumn('name', function ($q) {
                return $q->name;
            })
            ->addColumn('status', function ($q) {
                return $q->status;
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('customer.dynamic-template.edit', [$q->id]) . "' title='Edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this template?"
                                        data-action=' . route('customer.dynamic-template.destroy', [$q->id]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>'.
                    '<button class="btn btn-sm btn-primary export ml-2" data-id="'.$q->id.'" type="button"><i class="fa fa-sign-out-alt"></i></button>';
            })

            ->rawColumns(['action'])
            ->toJson();
    }


    public function create(){

        return view('customer.dynamic_template.create');
    }

    public function store(Request  $request){
        $request->validate([
            'name'=>'required'
        ]);
        $all_inputs = [];
        if ($request->inputes) {
            foreach ($request->inputes as $input) {
                if($input) {
                    $input = str_replace(' ', '_', str_replace('-', '_', $input));
                    $all_inputs[] = strtolower($input);
                }
            }
        }

        $template= new DynamicTemplate();
        $template->customer_id=auth('customer')->user()->id;
        $template->name=$request->name;
        $template->status=$request->status;
        $template->fields=json_encode($all_inputs);
        $template->save();

        return redirect()->route('customer.dynamic-template.index')->with('success', 'Dynamic Template Successfully Create');
    }

    public function edit($template){
        $template=DynamicTemplate::findOrFail($template);

        $data['template']=$template;

        $data['fields']=$template->fields?json_decode($template->fields):'';

        return view('customer.dynamic_template.edit', $data);
    }


    public function update($template, Request $request){
        $request->validate([
            'name'=>'required'
        ]);
        $template=DynamicTemplate::findOrFail($template);
        $all_inputs = [];
        if ($request->inputes) {
            foreach ($request->inputes as $input) {
                if($input) {
                    $input = str_replace(' ', '_', str_replace('-', '_', $input));
                    $all_inputs[] = strtolower($input);
                }
            }
        }

        $template->name=$request->name;
        $template->status=$request->status;
        $template->fields=json_encode($all_inputs);
        $template->save();


        return redirect()->route('customer.dynamic-template.index')->with('success', 'Dynamic Template Successfully Updated');
    }

    public function destroy($template){
        $template=DynamicTemplate::findtOrFail($template);
        $template->delete();

        return redirect()->route('customer.dynamic-template.index')->with('success', 'Dynamic Template Successfully Deleted');
    }

    public function export(Request $request){
        $template=DynamicTemplate::findOrFail($request->id);

        if(!$template->fields){
            return redirect()->route('customer.dynamic-template.index')->withErrors(['failed'=>'Empty value']);
        }

        $token=Str::random(5);
        if ($request->type=='csv'){
            $fileName = 'template-'.$token.'.csv';

            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
            );

            $fields=$template->fields?json_decode($template->fields):'';
            $all_fields=[];
            foreach ($fields as $field) {
                $all_fields[]=  $field;
            }

            $callback = function () use ($all_fields) {
                $file = fopen('php://output', 'w');

                fputcsv($file, $all_fields);

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }elseif($request->type=='xls'){
            $fileName = 'template-'.$token.'.xls';

            $headers = array(
                "Content-type" => "text/plain",
                "Content-Disposition" => "attachment; filename=$fileName",
            );

            $fields=$template->fields?json_decode($template->fields):'';

            $all_fields='';
            foreach ($fields as $key=>$field) {
                if($key==0) {
                    $all_fields =$field;
                }else {
                    $all_fields = $all_fields . '/' . $field;
                }
            }
            $headerRow = "ID\tName\tEmail\r\n";
            $all_fields=$headerRow;
            $callback = function () use ($all_fields) {
                $file = fopen('php://output', 'w');

                fputs($file, $all_fields);

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }
    }
}
