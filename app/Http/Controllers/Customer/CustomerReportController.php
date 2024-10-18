<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\MessageReport;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerReportController extends Controller
{
    //  Transactions Report
    public function transactions(Request  $request){

        $data['request_data']=$request->only('main_type','sub_type','date');
        $data['customers']=Customer::orderByDesc('created_at')->where('added_by', 'reseller')->where('admin_id', auth('customer')->user()->id)->get();

        return view('customer.customer_reports.transactions', $data);
    }

//getAllTransactions

    public function getAllTransactions(Request $request){

        $customers=Customer::where('added_by', 'reseller_customer')->where('admin_id', auth('customer')->user()->id)->pluck('id');

        $reports = Transactions::orderByDesc('created_at')->whereIn('customer_id', $customers);

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

            ->addColumn('profile', function ($q) {
                $profile='<div><h6 class="d-block">'.$q->customer->full_name.'</h6><b>'.$q->customer->email.'</b></div>';
                return $profile;
            })

            ->addColumn('amount', function ($q) {

                return formatNumberWithCurrSymbol($q->amount);
            })

            ->addColumn('type', function ($q) {

                return str_replace('_','-', $q->type);
            })

            ->addColumn('status', function ($q) {
                if($q->type=='paid'){
                    $type=' <span class="badge badge-success">Paid</span>';
                }else{
                    $type=' <span class="badge badge-danger">Unpaid</span>';
                }
                return $type;
            })

            ->rawColumns(['amount','status','type','profile'])

            ->toJson();
    }


    //Message Reports
    public function message_report(Request  $request){
        $data['request_data']=$request->only('main_type','sub_type','date');
        $data['customers']=Customer::where('added_by', 'reseller')->where('admin_id', auth('customer')->user()->id)->get();

        return view('customer.customer_reports.message', $data);
    }


    public function getAllReports(Request $request){
        $customers=Customer::where('added_by', 'reseller')->where('admin_id', auth('customer')->user()->id)->pluck('id');

        $reports = MessageReport::orderByDesc('created_at')->whereIn('customer_id',$customers);

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
            ->addColumn('profile', function ($q) {
                $profile='<div><h6 class="d-block">'.$q->customer->full_name.'</h6><b>'.$q->customer->email.'</b></div>';
                return $profile;
            })
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

            ->rawColumns(['details','profile'])

            ->toJson();
    }
}
