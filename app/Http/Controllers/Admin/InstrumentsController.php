<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstrumentDetail;
use Illuminate\Http\Request;
use App\Models\Instrument;
use Illuminate\Support\Facades\DB;

class InstrumentsController extends Controller
{
    public function index(){
        return view('admin.instruments.index');
    }
    public function getAll(){
        $instruments = Instrument::orderBy('created_at', 'desc');
        return datatables()->of($instruments)

            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.instruments.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this Instrument ?"
                                    data-action=' . route('admin.instruments.destroy', [$q]) . '
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
        return view('admin.instruments.create');
    }
    public function store(Request $request){

        DB::beginTransaction();
        $request->validate([
            'title' => 'required',
        ]);

//        dd($request->all());

        try {
            $instruments = new Instrument();
            $instruments->title = $request->title;
            $instruments->description = $request->description;
            $imageName = '';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('/uploads'), $imageName);
            }
            $instruments->image = $imageName;
            $instruments->save();

            if ($request->table_title) {
                foreach ($request->table_title as $key => $title) {
                    if (isset($request->table_head[$key]) && isset($request->table_body[$key])) {
                        $tableHead=[];
                        $tableBody=[];

                        foreach($request->table_head[$key] as $thead){
                            $tableHead[]= $thead;
                        }
                        foreach($request->table_body[$key] as $tbody){
                            $tableBody[]=$tbody;
                        }

                        $insDetails = new InstrumentDetail();
                        $insDetails->instrument_id = $instruments->id;
                        $insDetails->title = $title;
                        $insDetails->key = json_encode($tableHead);
                        $insDetails->value = json_encode($tableBody);
                        $insDetails->save();
                    }
                }
            }


            DB::commit();

            return back()->with('success', 'Instruments Successfully Created');
        }catch(\Exception $ex){
            DB::rollBack();
            return back()->withErrors(['failed'=>$ex->getMessage()]);
        }
    }
    public function edit(Instrument $instrument){
        $data['instruments']=$instrument;
        $data['instrumentDetails']=InstrumentDetail::where('instrument_id', $instrument->id)->get();
        return view('admin.instruments.edit',$data);
    }
    public function update(Request $request,Instrument $instrument){

        DB::beginTransaction();
        $request->validate([
            'title' => 'required',
        ]);


        try {
            $instrument->title = $request->title;
            $instrument->description = $request->description;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('/uploads'), $imageName);
                $instrument->image = $imageName;
            }

            $instrument->save();

            InstrumentDetail::where('instrument_id', $instrument->id)->delete();

            if ($request->table_title) {
                foreach ($request->table_title as $key => $title) {
                    if (isset($request->table_head[$key]) && isset($request->table_body[$key])) {
                        $tableHead=[];
                        $tableBody=[];

                        foreach($request->table_head[$key] as $thead){
                            $tableHead[]= $thead;
                        }
                        foreach($request->table_body[$key] as $tbody){
                            $tableBody[]=$tbody;
                        }

                        $insDetails = new InstrumentDetail();
                        $insDetails->instrument_id = $instrument->id;
                        $insDetails->title = $title;
                        $insDetails->key = json_encode($tableHead);
                        $insDetails->value = json_encode($tableBody);
                        $insDetails->save();
                    }
                }
            }

            DB::commit();

            return back()->with('success', 'Instruments Successfully Updated');
        }catch(\Exception $ex){
            DB::rollBack();
            return back()->withErrors(['failed'=>$ex->getMessage()]);
        }

    }
    public function destroy(Instrument $instrument){
        InstrumentDetail::where('instrument_id', $instrument->id)->delete();
        $instrument->delete();
        return back()->with('success', 'Instruments Successfully Delete');
    }
}
