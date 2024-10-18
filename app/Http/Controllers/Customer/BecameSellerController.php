<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BecameReseller;
use App\Models\Plan;
use Illuminate\Http\Request;


class BecameSellerController extends Controller
{
    public function create()
    {
        $customer=auth('customer')->user();
        if($customer->type=='normal'){
            $currentPlan=Plan::where('id',$customer->plan->plan_id)->first();
            if($currentPlan && $currentPlan->is_reseller=='no'){
                return redirect()->route('customer.billing.reseller.plan');

            }
        }
        $data['resellerRequest'] = BecameReseller::where('customer_id', $customer->id)->first();
        return view('became_seller.create', $data);
    }

    public function store(Request $request)
    {
        $resellerRequest = BecameReseller::where('customer_id', auth('customer')->user()->id)->first();
//        if($resellerRequest || $resellerRequest->status !='pending'){
//            return  redirect()->route('customer.dashboard')->withErrors(['failed'=>'You already submitted request']);
//        }
        $request->validate([
            'city' => 'required',
            'address' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'nid_card' => 'required',
            'td_license' => 'required',
            'picture' => 'required',
        ]);

        $uniqueName = auth('customer')->user()->first_name . '-' . auth('customer')->user()->id;
        if ($request->hasFile('nid_card')) {
            $file = $request->file('nid_card');
            $imageName = $uniqueName . '-nid-card-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads/reseller'), $imageName);
            $nid_card = $imageName;
        }

        if ($request->hasFile('td_license')) {
            $file = $request->file('td_license');
            $imageName = $uniqueName . '-td-license-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads/reseller'), $imageName);
            $td_license = $imageName;
        }
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $imageName = $uniqueName . '-picture-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads/reseller'), $imageName);
            $picture = $imageName;
        }

        $attachedData = [
            'nid_card' => $nid_card,
            'picture' => $picture,
            'td_license' => $td_license
        ];
        if ($resellerRequest && $resellerRequest->status=='rejected') {
            $reseller = $resellerRequest;
        } else {
            $reseller = new BecameReseller();
        }

        $reseller->customer_id = auth('customer')->user()->id;
        $reseller->city = $request->city;
        $reseller->address = $request->address;
        $reseller->country = $request->country;
        $reseller->zip_code = $request->zip_code;
        $reseller->status = 'pending';
        $reseller->documents = json_encode($attachedData);
        $reseller->save();

        return redirect()->route('customer.dashboard')->with('success', 'Became a reseller form successfully submitted, wait until approved');
    }


}
