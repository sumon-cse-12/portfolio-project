<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{

    public function store(Request $request){
        try {
            $currentIp = $_SERVER['SERVER_ADDR'];
            $requestDomain='www.'.$request->domain;
            $results = dns_get_record($requestDomain,DNS_A);
            $requestIp=[];
            foreach($results as $result){
                if(isset($result['ip'])) {
                    $requestIp[] = $result['ip'];
                }
            }
            $host=parse_url(str_replace('www.','http://', trim($requestDomain)));
            if (!isset($host['host'])) {
                return response()->json(['message' => 'Host not found', 'status' => 'failed']);
            }
            $request['host']=$host['host'];

            if(!isset($requestIp[0]) ||  !in_array($currentIp, $requestIp)){
                return response()->json(['message'=> 'Domain should be pointed to '.'<b>'.$currentIp.'</b>', 'status' => 'failed']);
            }

            $customer = auth('customer')->user();
            $domain = $customer->domain()->first();

            if ($domain) {
                $request->validate([
                    'domain' => 'required|unique:domains,domain,' . $domain->id,
                    'host' => 'required|unique:domains,host,' . $domain->id,
                ]);
                $request['status'] = 'pending';
                $domain->update($request->only('domain','host','status'));
            } else {
                $request->validate([
                    'domain' => 'required|unique:domains',
                    'host' => 'required|unique:domains',
                ]);
                $customer->domain()->create(['domain' => $request->domain,'host'=>$request->host]);
            }

            return response()->json(['message'=> 'Domain successfully added', 'status' => 'success']);
        }catch(\Exception $ex){
            return response()->json(['message'=> $ex->getMessage(), 'status' => 'failed']);
        }
    }
    public function delete(){

        $customer=auth('customer')->user();
        $domain=$customer->domain()->first();
        $domain->status='deleted';
        $domain->save();

        return redirect()->back()->with('success', 'Domain successfully deleted');
    }

    public function test(Request $request){

        $hello=['dekmsdfk', 'ndskfhk'];

        foreach($hello as $hello){
            $alu[]=$hello;
        }
    }
}
