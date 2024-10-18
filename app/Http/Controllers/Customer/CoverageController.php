<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coverage;
use Illuminate\Http\Request;

class CoverageController extends Controller
{
    public function index()
    {
        return view('customer.coverage.index');
    }

    public function getAll()
    {
        $coverage = Coverage::orderByDesc('created_at')->where('added_by', 'reseller')->where('admin_id', auth('customer')->user()->id);

        return datatables()->of($coverage)
            ->addColumn('action', function (Coverage $q) {
                return "<a class='btn btn-sm btn-info' href='" . route('customer.coverage.edit', [$q]) . "'>Edit</a>  &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this coverage?"
                                        data-action=' . route('customer.coverage.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->addColumn('country', function (Coverage $q) {

                return strtoupper($q->country);
            })
            ->rawColumns(['action', 'country'])
            ->toJson();
    }

    public function create()
    {
        $current_plan = auth('customer')->user()->plan;
        if (!$current_plan || !isset($current_plan->coverage_ids)) {
            return redirect()->route('customer.billings')->withErrors(['failed' => 'Upgrade your plan to create plan']);
        }

        $coverages = Coverage::whereIn('id', json_decode($current_plan->coverage_ids))->get();
        $countries = [];
        foreach ($coverages as $coverage) {
            $countries[strtoupper($coverage->country)] = [
                'name' => getCountryCode()[strtoupper($coverage->country)]['name'],
                'code' => $coverage->country_code,
            ];
        }
        $data['countries'] = $countries;
        return view('customer.coverage.create', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'country' => 'required',
            'plain_sms' => 'required',
            'receive_sms' => 'required',
            'send_mms' => 'required',
            'receive_mms' => 'required',
            'send_voice_sms' => 'required',
            'receive_voice_sms' => 'required',
            'send_whatsapp_sms' => 'required',
            'receive_whatsapp_sms' => 'required'
        ]);
        $country_code = getCountryCode()[$request->country];

        if (!isset($country_code['code'])) {
            return redirect()->back()->withErrors(['failed' => 'Invalid Country code']);
        }

        $pre_counry = Coverage::where('country', $request->country)->where('added_by', 'reseller')->where('admin_id', auth('customer')->user()->id)->first();
        if ($pre_counry) {
            return redirect()->back()->withErrors(['failed' => 'This country already have in coverage list, try another one']);
        }

        $coverage = new Coverage();
        $coverage->added_by = 'reseller';
        $coverage->admin_id = auth('customer')->user()->id;
        $coverage->country = $request->country;
        $coverage->country_code = $country_code['code'];
        $coverage->plain_sms = $request->plain_sms;
        $coverage->receive_sms = $request->receive_sms;
        $coverage->send_mms = $request->send_mms;
        $coverage->receive_mms = $request->receive_mms;
        $coverage->send_voice_sms = $request->send_voice_sms;
        $coverage->receive_voice_sms = $request->receive_voice_sms;
        $coverage->send_whatsapp_sms = $request->send_whatsapp_sms;
        $coverage->receive_whatsapp_sms = $request->receive_whatsapp_sms;
        $coverage->save();

        return redirect()->route('customer.coverage.index')->with('success', 'Coverage Successfully Created');
    }

    public function edit(Coverage $coverage)
    {
        $data['coverage'] = $coverage;
        $current_plan = auth('customer')->user()->plan;
        if (!$current_plan || !isset($current_plan->coverage_ids)) {
            return redirect()->route('customer.billings')->withErrors(['failed' => 'Upgrade your plan to create plan']);
        }

        $coverages = Coverage::whereIn('id', json_decode($current_plan->coverage_ids))->get();
        $countries = [];
        foreach ($coverages as $coverage) {
            $countries[strtoupper($coverage->country)] = [
                'name' => getCountryCode()[strtoupper($coverage->country)]['name'],
                'code' => $coverage->country_code,
            ];
        }
        $data['countries'] = $countries;
        return view('customer.coverage.edit', $data);
    }

    public function update(Coverage $coverage, Request $request)
    {

        $coverage->plain_sms = $request->plain_sms;
        $coverage->receive_sms = $request->receive_sms;
        $coverage->send_mms = $request->send_mms;
        $coverage->receive_mms = $request->receive_mms;
        $coverage->send_voice_sms = $request->send_voice_sms;
        $coverage->receive_voice_sms = $request->receive_voice_sms;
        $coverage->send_whatsapp_sms = $request->send_whatsapp_sms;
        $coverage->receive_whatsapp_sms = $request->receive_whatsapp_sms;
        $coverage->save();

        return redirect()->route('customer.coverage.index')->with('success', 'Coverage Successfully Updated');
    }

    public function destroy(Coverage $coverage)
    {

        if ($coverage->id == '1' || $coverage->admin_id != auth('customer')->user()->id) {
            return redirect()->route('admin.coverage.index')->withErrors(['failed' => 'You can\'t delete this coverage']);
        }

        $coverage->delete();

        return redirect()->route('admin.coverage.index')->with('success', 'Coverage Successfully Deleted');
    }
}
