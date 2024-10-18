<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\BillingRequest;
use App\Models\Customer;
use App\Models\Domain;
use App\Models\EmailTemplate;
use App\Models\Label;
use App\Models\Number;
use App\Models\Plan;
use App\Models\Settings;
use App\Models\User;
use App\Models\VerifyCustomer;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CustomerLoginController extends Controller
{
    public function index()
    {
        $data['registration_status'] = get_settings('registration_status');
        return view('auth.login',$data);
    }

    public function authenticate(Request $request)
    {
        $credentials['email'] = trim($request->email);
        $credentials['password'] = trim($request->password);
        $credentials['status'] = 'active';

        if(get_settings('recaptcha_key', isset($domain->cutomer_id)?$domain->cutomer_id:'') && isset(json_decode(get_settings('recaptcha_key', isset($domain->cutomer_id)?$domain->cutomer_id:''))->recaptcha_secret_key)) {
            $data = array(
                'secret' => json_decode(get_settings('recaptcha_key'))->recaptcha_secret_key,
                'response' => $request->grecaptcha_response,
            );
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($verify);

            $captcha = json_decode($res);
            if ($captcha->success == false) {
                return redirect()->back()->withErrors(['failed'=>'Invalid Captcha, You are a freakin robot!'])->withInput();
            }
        }

        $customer = Customer::where(['email' => $credentials['email']])->first();

        if (isset($customer) && \Hash::check($credentials['password'], $customer->password)) {
            if(!$customer->email_verified_at) return back()->withErrors(['msg'=>'Please verify your email address.']);

            if ($customer && $customer->status != 'Active') return back()->withErrors(['msg' => 'Account temporary blocked. Contact with administrator']);

        }


        $remember_me = $request->has('remember_me') ? true : false;
        if (Auth::guard('customer')->attempt($credentials, $remember_me)) {

            if(auth('customer')->user()->type=='staff'){
                return redirect()->route('customer.chat.index');
            }

            return redirect()->route('customer.smsbox.overview');
        }
        return back()->withErrors(['msg' => 'Invalid email or password. Please try again.']);
    }

    public function logout()
    {
        auth('customer')->logout();
        return redirect()->route('login');
    }

    public function sign_up()
    {
        return view('auth.registration');
    }

    public function sign_up_create(Request $request)
    {
            if (get_settings('registration_status') != 'enable') {
                abort(404);
            }
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:customers',
                'password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                $errors=[];
                foreach($validator->errors()->messages() as $err){
                    $errors[]=isset($err[0])?$err[0]:'';
                }
                return back()->withErrors(['failed'=>$errors]);
            }
            $admin = User::first();

            $host = $request->getHost();
            $domain = Domain::where('host', $host)->where('status', 'approved')->first();
            if (get_settings('recaptcha_key', isset($domain->cutomer_id) ? $domain->cutomer_id : '') && isset(json_decode(get_settings('recaptcha_key', isset($domain->cutomer_id) ? $domain->cutomer_id : ''))->recaptcha_secret_key)) {
                $data = array(
                    'secret' => json_decode(get_settings('recaptcha_key'))->recaptcha_secret_key,
                    'response' => $request->grecaptcha_response,
                );
                $verify = curl_init();
                curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
                curl_setopt($verify, CURLOPT_POST, true);
                curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                $res = curl_exec($verify);

                $captcha = json_decode($res);
                if ($captcha->success == false) {
                    return redirect()->back()->withErrors(['failed' => 'Invalid Captcha, You are a freakin robot!'])->withInput();
                }
            }
            if ($domain) {
                $customer = Customer::find($domain->customer_id);
            }

            $plan = Plan::where('id', $request->plan_id)->first();
            if (!$plan) {
                return redirect()->back()->withErrors(['failed' => 'Please choose a plan and try again']);
            }
        DB::beginTransaction();
        try {
            $request['type'] = $plan->plan_type;

            $request['status'] = 'inactive';
            if (isset($customer)) {
                $request['admin_id'] = $customer->id;
                $request['added_by'] = $customer->type;
                $newCustomer = $customer->customers()->create($request->all());
            } else {
                $request['admin_id'] = $admin->id;
                $request['added_by'] = 'admin';
                $newCustomer = $admin->customers()->create($request->all());
            }

//        Customer Label
            $label = new Label();
            $label->title = 'new';
            $label->customer_id = $newCustomer->id;
            $label->color = 'red';
            $label->status = 'active';
            $label->save();


            //TODO:: sent a mail here for confirmation mail

            $token = Str::random(32);
            $verify = new VerifyCustomer();
            $verify->customer_id = $newCustomer->id;
            $verify->token = $token;
            $verify->save();

            $wallet = new Wallet();
            $wallet->customer_id = $newCustomer->id;
            $wallet->credit = 0;
            $wallet->status = 'approved';
            $wallet->save();

            $number = Number::where('is_default', 'yes')->first();
            if ($number) {
                $time = Carbon::now()->addMonths(1);
                $newCustomer->numbers()->create(['number_id' => $number->id, 'number' => $number->number, 'expire_date' => $time, 'cost' => $number->sell_price]);
            }


            if (isset($customer)) {
                $mailSett = $customer->settings()->where('name', 'smtp_setting')->first();
                $mailSett = isset($mailSett->value) ? json_decode($mailSett->value) : '';
                $config = array(
                    'driver' => 'smtp',
                    'host' => $mailSett->host,
                    'port' => $mailSett->port,
                    'from' => array('address' => $mailSett->from, 'name' => $mailSett->name),
                    'encryption' => $mailSett->encryption,
                    'username' => $mailSett->username,
                    'password' => $mailSett->password,
                );
                $emailTemplate = EmailTemplate::where('added_by', $customer->type)->where('type', 'registration')->where('user_id', $customer->id)->first();
                if ($emailTemplate) {
                    $route = route('customer.verify.view', ['customer' => $newCustomer->id, 'token' => $token]);
                    $regTemp = str_replace('{customer_name}', $newCustomer->first_name . ' ' . $newCustomer->last_name, $emailTemplate->body);
                    $regTemp = str_replace('{click_here}', "<a href=" . $route . ">" . trans('admin.click_here') . "</a>", $regTemp);
                    SendMail::dispatch($newCustomer->email, $emailTemplate->subject, $regTemp, $config);
                }
            } else {
                $emailTemplate = get_email_template('registration');
                $mailHost = get_settings('mail_host');
                $mailUsername = get_settings('mail_username');
                if (!$mailHost || !$mailUsername) {
                    throw new \Exception("You can not sign-up at this moment, Try again after sometimes later");
                }
                if ($emailTemplate) {
                    $route = route('customer.verify.view', ['customer' => $newCustomer->id, 'token' => $token]);
                    $regTemp = str_replace('{customer_name}', $newCustomer->first_name . ' ' . $newCustomer->last_name, $emailTemplate->body);
                    $regTemp = str_replace('{click_here}', "<a href=" . $route . ">" . trans('admin.click_here') . "</a>", $regTemp);
                    SendMail::dispatch($newCustomer->email, $emailTemplate->subject, $regTemp);
                }
            }

            if ($request->plan_id && \Module::has('PaymentGateway') && \Module::find('PaymentGateway')->isEnabled()) {
                auth('customer')->login($newCustomer);
                $data['plan'] = Plan::where('id', $request->plan_id)->firstOrFail();
                DB::commit();
                return view('customer.demo_view', $data)->with('success', trans('layout.message.registration_success'));
            }else if($domain){
                $planReq = new BillingRequest();
                $planReq->admin_id = $plan->admin_id;
                $planReq->customer_id = $newCustomer->id;
                $planReq->plan_id = $plan->id;
                $planReq->other_info = json_encode($request->only('payment_type'));
                $planReq->save();
                DB::commit();
            }else{
                DB::commit();
            }

            return redirect()->route('login')->with('success', 'Congratulations !! An email has been sent to your mail address');
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return back()->withErrors(['failed'=>$ex->getMessage()]);
        }
    }

    public function verifyView(Request $request){
        $customer=$request->customer;
        $data['customer'] = Customer::find($customer);

        return view('mail.verify_customer',$data);
    }

    public function verify(Request $request)
    {
        $customer = $request->customer;
        $token = $request->token;

        $customer = Customer::find($customer);

        if (!$customer) return redirect()->route('login')->with('fail', 'Invalid token or token has been expired');

        $verify = VerifyCustomer::where(['customer_id' => $customer->id, 'token' => $token, 'status' => 'pending'])->first();

        if (!$verify) return redirect()->route('login')->with('fail', 'Invalid token or token has been expired.');

        $customer->status = 'active';
        $customer->email_verified_at = now();
        $customer->save();

        $verify->delete();

        return redirect()->route('login')->with('success', 'Email successfully verified');
    }
    public function loginAsAdmin(Request $request){
        if(!$request->id) abort(404);
        $customer_id = auth('customer')->user()->id;
        session()->forget('customer_session_'.$customer_id);
        auth('customer')->logout();
        return redirect()->route('admin.dashboard')->with('success',trans('You are now logged as Admin'));
    }

}
