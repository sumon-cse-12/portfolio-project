<?php

namespace App\Http\Controllers\Admin;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function show_form()
    {
        $data['registration_status'] = get_settings('registration_status');
        return view('admin.forgot_password.password_reset_form',$data);
    }

    public function resetPassword(Request $request)
    {

        return view('mail.password-reset');
    }
    public function sent_email(Request $request)
    {
        $admin = User::where('email', $request->email)->first();
        if (!$admin) return back()->with('fail', 'Account not found with this email');
        $token = substr(md5(mt_rand()), 0, 30);

        $data = [
            'user_id' => $admin->id,
            'token' => $token
        ];
        DB::table('password_reset')->insert($data);

        //TODO::send email here with type like customer or admin
        //URL: password/reset?customer=1&token=alksjdflasjkdfl&type=customer
        $emailTemplate = get_email_template('forget_password');
        if ($emailTemplate) {
            $route = route('admin.password.reset.confirm',['admin'=>$admin->id,'token'=>$token,'type'=>'admin']);

            $regTemp = str_replace('{customer_name}', $admin->name, $emailTemplate->body);
            $regTemp = str_replace('{reset_url}', "<a href=" . $route . ">" . trans('admin.settings.click_here') . "</a>", $regTemp);
            SendMail::dispatch($admin->email, $emailTemplate->subject, $regTemp);
        }
        return redirect()->route('admin.login')->with('success', 'An instruction has been sent to your email');
    }

    public function reset_form(Request $request)
    {

        $data['id'] = $id = $request->admin;
        $data['type'] = $type = $request->type;
        $data['token'] = $token = $request->token;
        $reset = DB::table('password_reset')->where(['user_id' => $id, 'token' => $token])->first();
        if (!$reset || !in_array($type, ['admin'])) return redirect()->route('login')->with('fail', 'Token is invalid or has been expired');

        $user = User::find($id);

        if (!$user) return redirect()->route('login')->with('fail', 'User has been removed or blocked');

        return view('admin.forgot_password.password_confirm_form', $data);


    }

    public function reset_confirm(Request $request)
    {
        $request->validate([
            'password'=>'required|min:6|confirmed',
        ]);
        $id = $request->customer;
        $type = $request->type;
        $token = $request->token;
        $reset = DB::table('password_reset')->where(['user_id' => $id, 'token' => $token])->first();
        if (!$reset || !in_array($type, ['admin'])) return redirect()->route('login')->with('fail', 'Token is invalid or has been expired');

        $user = User::find($id);

        if (!$user) return redirect()->route('admin.login')->with('fail', 'User has been removed or blocked');

        $user->password= bcrypt($request->password);
        $user->save();

        DB::table('password_reset')->where(['user_id' => $id])->delete();

        return redirect()->route('admin.login')->with('success', 'Successfully reset your password');
    }
}
