<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AuthorizationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthorizationController extends Controller
{
    public function index()
    {
        $user = auth('customer')->user();
        $data['authorization_token'] = AuthorizationToken::where('customer_id', $user->id)->first();
        if(!api_availability()){
            return abort('404');
        }

        return view('customer.api.index', $data);
    }

    public function store(Request $request)
    {
        $user = auth('customer')->user();

        $access_token= $user->createToken($user->email)->plainTextToken;

        $preToken = AuthorizationToken::where('customer_id', $user->id)->first();
        $authorization = isset($preToken) ? $preToken : new AuthorizationToken();
        $authorization->access_token = $access_token;
        $authorization->customer_id=$user->id;
        $authorization->refresh_token = $access_token;
        $authorization->save();

        return redirect()->route('customer.authorization.token.create')->with('success', 'API Token successfully updated');
    }
}
