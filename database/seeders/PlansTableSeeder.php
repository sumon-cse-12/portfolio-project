<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
        'title'=>'Trial', 'plan_type'=>'normal','price'=>'0','status'=>'active','added_by'=>'admin','admin_id'=>'1',
            'sms_sending_limit'=>'0','max_contact'=>'0','contact_group_limit'=>'0',
        'sms_unit_price'=>'0','free_sms_credit'=>'0','short_description'=>'Trail Plan',
            'created_at'=>now(),'updated_at'=>now(),'coverage_ids'=>json_encode(["1"])];

        $coverage=['country'=>'us','country_code'=>'1','plain_sms'=>'1','receive_sms'=>'1','send_mms'=>'1','receive_mms'=>'1','send_voice_sms'=>'1',
            'receive_voice_sms'=>'1','send_whatsapp_sms'=>'1','receive_whatsapp_sms'=>'1','added_by'=>'admin','admin_id'=>'1'];


        \App\Models\Plan::create($data);
        \App\Models\Coverage::create($coverage);
    }
}
