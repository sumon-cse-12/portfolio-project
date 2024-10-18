<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\MessageReport;
use App\Models\Plan;
use App\Models\Report;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

//  Transactions Report
    public function transactions(Request  $request){

        $data['request_data']=$request->only('main_type','sub_type','date');
        $data['customers']=Customer::orderByDesc('created_at')->get();

        return view('customer.report.transactions', $data);
    }

//getAlltransactions

    public function getAllTransactions(Request $request){


        $reports = Transactions::orderByDesc('created_at')->where('added_by', auth('customer')->user()->type)->where('customer_id', auth('customer')->user()->id);

        if($request->main_type){
            $reports = $reports->where('type', $request->main_type);
        }

        if($request->date){
            $dates= explode('-', $request->date);
            $fromDate= isset($dates[0])?$dates[0]:Carbon::now()->subDays(3);
            $toDate= isset($dates[1])?$dates[1]:Carbon::now();
            $reports = $reports->whereBetween('created_at',[$fromDate, $toDate]);
        }

//        $reports=$reports->get();


        return datatables()->of($reports)


            ->addColumn('amount', function ($q) {

                return formatNumberWithCurrSymbol($q->amount);
            })

            ->addColumn('type', function ($q) {

                return str_replace('_','-', $q->type);
            })

            ->addColumn('status', function ($q) {
                if($q->status=='paid'){
                    $type=' <span class="badge badge-success">Paid</span>';
                }else{
                    $type=' <span class="badge badge-danger">Unpaid</span>';
                }
                return $type;
            })

            ->rawColumns(['amount','status','type',])

            ->toJson();
    }


    //Message Reports
    public function message_report(Request  $request){
        $data['request_data']=$request->only('main_type','sub_type','date');

        return view('customer.report.message_report', $data);
    }


    public function getAllReports(Request $request){

        $reports = MessageReport::orderByDesc('created_at')->where('customer_id', auth('customer')->user()->id);

        if($request->destination){
            $reports = $reports->where('sent_type', $request->destination);
        }
        if($request->type){
            if($request->type=='sms'){
                $type='SMS';
            }else if($request->type=='whatsapp'){
                $type='Whatsapp';
            }else if($request->type=='mms'){
                $type='MMS';
            }else if($request->type=='voicecall'){
                $type='Voice SMS';
            }
        }
        if($request->date){
            $dates= explode('-', $request->date);
            $fromDate= isset($dates[0])?$dates[0]:Carbon::now()->subDays(3);
            $toDate= isset($dates[1])?$dates[1]:Carbon::now();
            $reports = $reports->whereBetween('created_at',[$fromDate, $toDate]);
        }

        return datatables()->of($reports)

            ->addColumn('details', function ($q) {
                $type='';

                if($q->type=='sms'){
                    $type='SMS';
                }else if($q->type=='whatsapp'){
                    $type='Whatsapp';
                }else if($q->type=='mms'){
                    $type='MMS';
                }else if($q->type=='voicecall'){
                    $type='Voice SMS';
                }
                $type='<h6>Type:'.$type.'</h6>';
                $destination='<h6>Destination:'.ucfirst($q->sent_type).'</h6>';
                $date='<h6>Date:'.formatDate($q->created_at).'</h6>';

                return '<div>'.$type.$destination.$date.'</div>';
            })

            ->rawColumns(['details'])

            ->toJson();
    }

}
