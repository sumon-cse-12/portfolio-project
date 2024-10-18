<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Report;
use App\Models\TopUpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopUpController extends Controller
{
    public function request()
    {

        return view('customer.topup.request');
    }


    public function getAllRequest()
    {
        $customer = auth('customer')->user();
        if ($customer->type == 'master_reseller') {
            $topup_requests = TopUpRequest::where('admin_id', $customer->id)->whereIn('customer_type', ['reseller', 'master_reseller_customer'])->orderByDesc('created_at')->get();
        } else if ($customer->type == 'reseller') {
            $topup_requests = TopUpRequest::where('admin_id', $customer->id)->where('customer_type', 'reseller_customer')->orderByDesc('created_at')->get();
        }

        $sellerWallet = $customer->wallet()->first();
        $credit = $sellerWallet->credit;

        return datatables()->of($topup_requests)
            ->addColumn('customer', function ($q) {
                return "<a href='#'>" . $q->customer->full_name . "</a>";
            })
            ->addColumn('credit', function ($q) {
                $formatAmount=0;
                if(isset($q->customer->plan->masking_rate) && $q->credit_type=='masking'){
                    $formatAmount=$q->customer->plan->masking_rate * $q->credit;
                }elseif(isset($q->customer->plan->non_masking_rate) && $q->credit_type=='non_masking'){
                    $formatAmount=$q->customer->plan->non_masking_rate * $q->credit;
                }
                $credit=$q->credit.'<hr class="m-0"><span>'.formatNumberWithCurrSymbol($formatAmount).'</span>';
                return $credit;
            })
            ->addColumn('created_at', function ($q) {
                $date=formatDate($q->created_at);
                return $date;
            })
            ->addColumn('credit_type', function ($q) {
                if($q->credit_type=='masking'){
                    return "SenderID";
                }else{
                    return "Non SenderID";
                }
            })

            ->addColumn('payment_status', function ($q) {
                if($q->payment_status=='paid'){
                    $payment_status= '<strong class="text-success"> '.ucfirst($q->payment_status).' </strong>';
                }else{
                    $payment_status= '<strong class="text-danger"> '.ucfirst($q->payment_status).' </strong>';
                }
                return $payment_status;
            })

            ->addColumn('status', function ($q) {
                $status='';
                if($q->status=='pending'){
                    $status='<span class="badge badge-danger p-2">'.ucfirst($q->status).'</span>';
                }else if($q->status=='approved'){
                    $status='<span class="badge badge-success p-2">'.ucfirst($q->status).'</span>';
                }else{
                    $status='<span class="badge badge-info p-2">'.ucfirst($q->status).'</span>';
                }
                return $status;
            })
            ->addColumn('action', function ($q) use ($credit) {
                $approveBtn = '';
                if ($q->status == 'pending') {
                        if ($q->credit <= $credit) {
                            $approveBtn = '<button class="mr-1 btn btn-sm btn-info" data-message="Are you sure you want to approved the request ?"
                                        data-action=' . route('customer.topup.request.status') . '
                                        data-input={"id":"' . $q->id . '","status":"approved"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Approve</button>';
                        } else {
                            $approveBtn = '<button class="mr-1 btn btn-sm btn-warning disabled" disabled>Low Credit</button>';
                        }

                    return $approveBtn . ' <button class="btn btn-sm btn-danger" data-message="Are you sure you want to reject the request ?"
                                        data-action=' . route('customer.topup.request.status') . '
                                        data-input={"id":"' . $q->id . '","status":"rejected"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Reject</button>';
                }else if($q->status=='rejected'){
                    return '<button class="mr-1 btn btn-sm btn-danger disabled" disabled>' . ucfirst($q->status) . '</button>';
                } else {
                    return '<button class="mr-1 btn btn-sm btn-success disabled" disabled>' . ucfirst($q->status) . '</button>';
                }
            })
            ->rawColumns(['action','status', 'customer','created_at','payment_status','credit'])
            ->toJson();
    }

    public function requestStatus(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'status' => 'required|in:approved,rejected'
            ]);
            $topup_request = auth()->user()->topup_requests->where('id', $request->id)->firstOrFail();
            if ($topup_request && $request->status == 'approved') {
                $customer = Customer::where('id', $topup_request->customer_id)->firstOrFail();
                $wallet = $customer->wallet;
                $maskingPreCredit = $wallet->masking_credit;

                $nonMaskingPreCredit = $wallet->non_masking_credit;
                $whatsappPreCredit = $wallet->whatsapp_credit;
                if ($topup_request->credit_type == 'masking') {
                    $wallet->masking_credit = $maskingPreCredit + $topup_request->credit;
                    $wallet->save();
                } else if ($topup_request->credit_type == 'whatsapp') {
                    $wallet->whatsapp_credit = $whatsappPreCredit + $topup_request->credit;
                    $wallet->save();
                } else if ($topup_request->credit_type == 'non_masking') {
                    $wallet->non_masking_credit = $nonMaskingPreCredit + $topup_request->credit;
                    $wallet->save();
                }

                //Report
                $report= new Report();
                $report->customer_id=$customer->id;
                $report->ref_id=$topup_request->id;
                $report->type='topup';
                $report->sub_type=$topup_request->credit_type;
                $report->amount='+'.$topup_request->credit;
                $report->save();


                $topup_request->status = 'approved';
                $topup_request->payment_status='paid';
                $topup_request->save();

                $sellerWallet = auth('customer')->user()->wallet()->first();
                $maskingCredit = $sellerWallet->masking_credit;
                $nonMaskingCredit = $sellerWallet->non_masking_credit;
                $whatsappCredit = $sellerWallet->whatsapp_credit;

                $pMaskingCredit = $maskingCredit - $topup_request->credit;
                $pNonMaskingCredit = $nonMaskingCredit - $topup_request->credit;
                $pWhatsappCredit = $whatsappCredit - $topup_request->credit;
                $approvedCredit = 0;
                if ($topup_request->credit_type == 'masking' && $topup_request->credit <= $maskingCredit) {
                    $sellerWallet->masking_credit = $pMaskingCredit;
                    $sellerWallet->save();
                    $approvedCredit = $pMaskingCredit;

                    //Report
                    $report= new Report();
                    $report->customer_id=auth('customer')->user()->id;
                    $report->ref_id=$topup_request->id;
                    $report->type='topup';
                    $report->sub_type='masking';
                    $report->amount='-'.$pMaskingCredit;
                    $report->save();

                } else if ($topup_request->credit_type == 'whatsapp' && $topup_request->credit <= $whatsappCredit) {
                    $sellerWallet->whatsapp_credit = $pWhatsappCredit;
                    $sellerWallet->save();
                    $approvedCredit = $pWhatsappCredit;

                    //Report
                    $report= new Report();
                    $report->customer_id=auth('customer')->user()->id;
                    $report->ref_id=$topup_request->id;
                    $report->type='topup';
                    $report->sub_type='whatsapp';
                    $report->amount='-'.$pWhatsappCredit;
                    $report->save();
                } else if ($topup_request->credit_type == 'non_masking' && $topup_request->credit <= $nonMaskingCredit) {
                    $sellerWallet->non_masking_credit = $pNonMaskingCredit;
                    $sellerWallet->save();
                    $approvedCredit = $pNonMaskingCredit;

                    //Report
                    $report= new Report();
                    $report->customer_id=auth('customer')->user()->id;
                    $report->ref_id=$topup_request->id;
                    $report->type='topup';
                    $report->sub_type='non_masking';
                    $report->amount='-'.$pNonMaskingCredit;
                    $report->save();
                }else{
                    throw new \Exception('Low credit, please top-up and try again');
                }
            }

            if ($request->status == 'rejected') {
                $topup_request->status = 'rejected';
                $topup_request->save();
            }
            cache()->forget('wallet_'.$customer->id);
            DB::commit();
            return redirect()->back()->with('success', 'TopUp Request Status Successfully Changes');
        } catch (\Throwable $ex) {
            DB::rollback();
            return redirect()->back()->withErrors(['fail' => $ex->getMessage()]);
        }
    }

}
