<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicGateway;
use App\Models\GatewayPrefill;
use App\Models\Number;
use App\Models\SenderId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GatewayController extends Controller
{
    protected $whiteListFunctions=[
        'base64_encode'
    ]; // this will be used to whitelist the execution function on DynamicGateway Store

    public function index()
    {
        return view('admin.gateway.index');
    }

    public function getAll(Request $request)
    {
        $gateways = DynamicGateway::orderByDesc('created_at');

        $gateways = $gateways->get();


        return datatables()->of($gateways)
            ->addColumn('name', function ($q) {
                return $q->name;
            })
            ->addColumn('status', function ($q) {
                $status = '';
                if ($q->status == 'active') {
                    $status = '<strong class="badge badge-success text-white">' . ucfirst($q->status) . '</strong>';
                } else {
                    $status = '<strong class="badge badge-danger text-white">' . ucfirst($q->status) . '</strong>';
                }
                return $status;
            })
            ->addColumn('weblink', function ($q) {
                $weblink = json_decode($q->weblink);
                return isset($weblink->url) ? $weblink->url : '';
            })
            ->addColumn('message_key', function ($q) {
                return $q->message_key;
            })
            ->addColumn('to_mobile_key', function ($q) {
                return $q->to_mobile_key;
            })
            ->addColumn('from_mobile_key', function ($q) {
                return $q->from_mobile_key;
            })
            ->addColumn('action', function ($q) {
                return '<a class="btn btn-sm custom-btn-sm btn-info edit text-left"
                                                   href="' . route('admin.gateway.edit', [$q->id]) . '">Edit</a>
                                                   <button class="btn btn-sm btn-danger text-white custom-btn-sm ml-2" data-message="' . trans('customer.message.gateway_delete_warn') . '"
                                                                    data-action=' . route('admin.gateway.destroy', [$q->id]) . '
                                                                    data-input={"_method":"delete"}
                                                                    data-toggle="modal" data-target="#modal-confirm">' . trans('customer.delete') . '</button>';
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }

    public function create()
    {
        $data['gatewayPrefill'] = GatewayPrefill::pluck('name');
        return view('admin.gateway.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'name' => 'required|unique:dynamic_gateways',
                'weblink_value' => 'required',
                'message_key' => 'required',
                'to_mobile_key' => 'required',
                'from_mobile_key' => 'required',
            ]);

            $weblink = [
                'method' => $request->url_type,
                'url' => $request->weblink_value,
            ];

            $others = [];
            if ($request->key) {
                foreach ($request->key as $key => $keyVal) {
                    $others[$keyVal] = isset($request->value[$key]) ? $request->value[$key] : null;
                }
            }
            $headers = [];
            if ($request->header_key) {
                foreach ($request->header_key as $key => $keyVal) {
                    $headers[$keyVal] = isset($request->header_value[$key]) ? $request->header_value[$key] : null;
                }
            }

            $days = [];
            if ($request->offday) {
                foreach ($request->offday as $key => $day) {
                    $days[] = strtolower($day);
                }
            }
            $request['offdays'] = json_encode($days);

            $gateway = new DynamicGateway();
            $gateway->admin_id = auth()->user()->id;
            $gateway->name = $request->name;
            $gateway->updated_from_gateway_prefill = 'false';
            $gateway->weblink = json_encode($weblink);
            $gateway->to_mobile_key = $request->to_mobile_key;
            $gateway->message_key = $request->message_key;
            $gateway->from_mobile_key = $request->from_mobile_key;
            $gateway->voice_sms_lang_key = $request->voice_sms_lang_key;
            $gateway->voice_sms_voice_key = $request->voice_sms_voice_key;
            $gateway->mms_mobile_key = $request->mms_mobile_key;
            $gateway->others = json_encode($others);
            $gateway->headers = json_encode($headers);
            $gateway->status = $request->status;
//            Sending Settings
            $gateway->start_time = $request->start_time;
            $gateway->end_time = $request->end_time;
            $gateway->offdays = $request->offdays;
            $gateway->daily_limit = $request->daily_limit;
            $gateway->monthly_limit = $request->monthly_limit;
            $gateway->minute_limit = $request->minute_limit;
            $gateway->send_limit = $request->send_limit; //limit according to minute_limit
            $gateway->save();

            DB::commit();
            return redirect()->route('admin.gateway.index')->with('success', trans('customer.message.gateway_added'));
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $ex->getMessage()])->withInput($request->all());
        }
    }

    public function edit(DynamicGateway $gateway)
    {
        $data['gateway'] = $gateway;
        $weblink = json_decode($gateway->weblink);
        $data['method'] = isset($weblink->method) ? $weblink->method : '';
        $data['url'] = isset($weblink->url) ? $weblink->url : '';
        $data['others'] = isset($gateway->others) ? json_decode($gateway->others) : '';
        $data['headers'] = isset($gateway->headers) ? json_decode($gateway->headers) : '';
        $data['offdays'] = json_decode($gateway->offdays);
        return view('admin.gateway.edit', $data);
    }

    public function update(DynamicGateway $gateway, Request $request)
    {

        DB::beginTransaction();
        $pre_gateway=$gateway->firstOrFail();

        try {
            if($pre_gateway->updated_from_gateway_prefill=='false') {
                if($pre_gateway->name !='Smpp') {
                    $request->validate([
                        'name' => 'required|unique:dynamic_gateways,name,' . $gateway->id,
                    ]);
                }
                $weblink = [
                    'method' => $request->url_type,
                    'url' => $request->weblink_value,
                ];
                $others = [];
                if ($request->key) {
                    foreach ($request->key as $key => $keyVal) {
                        $others[$keyVal] = isset($request->value[$key]) ? $request->value[$key] : null;
                    }
                }
                if ($request->new_key) {
                    foreach ($request->new_key as $key => $keyVal) {
                        $others[$keyVal] = isset($request->new_value[$key]) ? $request->new_value[$key] : null;
                    }
                }
                $headers = [];
                if ($request->header_key) {
                    foreach ($request->header_key as $key => $keyVal) {
                        $headers[$keyVal] = isset($request->header_value[$key]) ? $request->header_value[$key] : null;
                    }
                }
                if ($request->new_header_key) {
                    foreach ($request->new_header_key as $key => $keyVal) {
                        $headers[$keyVal] = isset($request->new_header_value[$key]) ? $request->new_header_value[$key] : null;
                    }
                }

                $to_mobile_key=$request->to_mobile_key;
                $message_key=$request->message_key;
                $from_mobile_key=$request->from_mobile_key;
                $mms_mobile_key=$request->mms_mobile_key;

            }else{
                if($pre_gateway->name !='Smpp') {
                    $request->validate([
                        'name' => 'required|unique:dynamic_gateways,name,' . $gateway->id
                    ]);
                }

                $to_mobile_key=$gateway->to_mobile_key;
                $message_key=$gateway->message_key;
                $from_mobile_key=$gateway->from_mobile_key;
                $mms_mobile_key=$gateway->mms_mobile_key;


                $gatewayPrefill = GatewayPrefill::whereId($gateway->gateway_prefill_id)->firstOrFail();
                $preFillData=$this->generatePrefillData($gatewayPrefill,$request->only(json_decode($gatewayPrefill->inputs)));
                $weblink=$preFillData->weblink;
                $others=$preFillData->others;
                $headers=$preFillData->headers;
                $gateway->inputs=json_encode($preFillData->inputs);
            }

            $days = [];
            if ($request->offday) {
                foreach ($request->offday as $key => $day) {
                    $days[] = strtolower($day);
                }
            }
            $request['offdays'] = json_encode($days);

            if($pre_gateway->name !='Smpp') {
                $gateway->name = $request->name;
            }
            $gateway->weblink = json_encode($weblink);
            $gateway->to_mobile_key = $to_mobile_key;
            $gateway->message_key = $message_key;
            $gateway->from_mobile_key = $from_mobile_key;
            $gateway->mms_mobile_key = $mms_mobile_key;
            $gateway->voice_sms_lang_key = $request->voice_sms_lang_key;
            $gateway->voice_sms_voice_key = $request->voice_sms_voice_key;
            $gateway->others = json_encode($others);
            $gateway->headers = json_encode($headers);
            $gateway->updated_from_gateway_prefill = $request->gateway_prefill_enable;
            $gateway->status = $request->status;
//            Sending Setting
            $gateway->start_time = $request->start_time;
            $gateway->end_time = $request->end_time;
            $gateway->offdays = $request->offdays;
            $gateway->daily_limit = $request->daily_limit;
            $gateway->monthly_limit = $request->monthly_limit;
            $gateway->minute_limit = $request->minute_limit;
            $gateway->send_limit = $request->send_limit;
            $gateway->save();

            DB::commit();
            return redirect()->route('admin.gateway.index')->with('success', trans('customer.message.gateway_update'));
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
        }
    }

    public function destroy(DynamicGateway $gateway)
    {
        $numbers=Number::where('dynamic_gateway_id', $gateway->id)->count();
        $senderId=SenderId::where('dynamic_gateway_id', $gateway->id)->count();

        if($numbers > 0 || $senderId > 0){
            return redirect()->route('admin.gateway.index')->withErrors(['msg'=>'Gateway is in use, can not be deleted']);
        }
        $gateway->delete();
        return redirect()->route('admin.gateway.index')->with('success', trans('customer.message.gateway_delete'));
    }

    public function getPrefill(Request $request)
    {
        $prefill = GatewayPrefill::whereName($request->name)->first(['description', 'from_mobile_key', 'headers', 'inputs', 'message_key', 'mms_mobile_key', 'name', 'others', 'to_mobile_key', 'weblink']);
        if (!$prefill) {
            return response()->json(['status' => 'failed', 'message' => 'Prefill not found with this name']);
        }

        return response()->json($prefill);
    }

    public function storePrefillGateway(Request $request)
    {
        $request->validate([
            'gateway' => 'required'
        ]);
        $gateway = GatewayPrefill::whereName($request->gateway)->firstOrFail();
        $preFillData=$this->generatePrefillData($gateway,$request->only(json_decode($gateway->inputs)));

        $dGateway = new DynamicGateway();
        $dGateway->admin_id = auth()->user()->id;
        $dGateway->gateway_prefill_id = $gateway->id;
        $dGateway->name = $gateway->name;
        $dGateway->weblink = json_encode(['method'=>$preFillData->weblink->method,'url'=>$preFillData->weblink->url]);
        $dGateway->to_mobile_key = $gateway->to_mobile_key;
        $dGateway->message_key = $gateway->message_key;
        $dGateway->from_mobile_key = $gateway->from_mobile_key;
        $dGateway->updated_from_gateway_prefill = 'true';
        $dGateway->mms_mobile_key = $gateway->mms_mobile_key;
        $dGateway->others = json_encode($preFillData->others);
        $dGateway->inputs = json_encode($preFillData->inputs);
        $dGateway->headers = json_encode($preFillData->headers);
        $dGateway->offdays = json_encode([]);
        $dGateway->save();

        return redirect()->route('admin.gateway.edit',[$dGateway])->with('success','Gateway successfully added');

    }

    private function generatePrefillData($gateway,$data){
        if (!$data) abort(404);
        /*
         * $data will be like ["tw_sid" => "qqqq","tw_token" => "aaaaaaa"]
        */
        $keysToCheck=[];
        foreach ($data as $key=>$value){
            $keysToCheck[]="{".$key."}";
        }
        $weblinkData=json_decode($gateway->weblink);
        if(!isset($weblinkData->url) || !isset($weblinkData->method)) abort(404);

        $weblink = Str::replace($keysToCheck, array_values($data), $weblinkData->url);

        $headers=[];
        $others=[];
        if($gateway->headers){
            foreach (json_decode($gateway->headers) as $key=>$header){
                $replacedHeader= Str::replace($keysToCheck, array_values($data),$header);
                if(Str::contains($replacedHeader,$this->whiteListFunctions)){
                    $codeToExecute=explode(']', (explode('[', $replacedHeader)[1]))[0];
                    $codeToExecuteParameter=explode(')', (explode('(', $replacedHeader)[1]))[0];
                    $executedResult=base64_encode($codeToExecuteParameter);
                    //dd($codeToExecute,$replacedHeader,$codeToExecuteParameter,$executedResult);
                    $replacedHeader=Str::replace("[".$codeToExecute."]",$executedResult,$replacedHeader);
                }
                $headers[$key]=$replacedHeader;
            }
        }

        if($gateway->others){
            foreach (json_decode($gateway->others) as $key=>$other){
                $replacedHeader= Str::replace($keysToCheck, array_values($data),$other);
                if(Str::contains($replacedHeader,$this->whiteListFunctions)){
                    $codeToExecute=explode(']', (explode('[', $replacedHeader)[1]))[0];
                    $codeToExecuteParameter=explode(')', (explode('(', $replacedHeader)[1]))[0];
                    $executedResult=base64_encode($codeToExecuteParameter); //TODO:: Need to validate more or find a new way to execute the code
                    $replacedHeader=Str::replace("[".$codeToExecute."]",$executedResult,$replacedHeader);
                }
                $others[$key]=$replacedHeader;
            }
        }
        $preFillData=[
            'weblink'=>[
                'method'=>$weblinkData->method,
                'url'=>$weblink,
            ],
            'others'=>$others,
            'inputs'=>$data,
            'headers'=>$headers
        ];
        return json_decode (json_encode ($preFillData), FALSE);
    }
}
