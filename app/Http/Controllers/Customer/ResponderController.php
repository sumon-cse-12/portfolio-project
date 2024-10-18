<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Responder;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResponderController extends Controller
{

    function generateChildLoop($all_responders){


       foreach ($all_responders as $s_responder){

           if($s_responder->parent_id){

                   $this->generateChildLoop($s_responder->responders);

                   $client = new Client(['Verify' => false]);
                   $client->post('https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f', [
                       'form_params' => [
                           'body' => 'With Parent '.$s_responder->id
                       ]
                   ]);

                   Log::info($s_responder->id);

           }else{

               Log::info($s_responder->id);

               $client = new Client(['Verify' => false]);
               $client->post('https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f', [
                   'form_params' => [
                       'body' => 'Without Parent '.$s_responder->id
                   ]
               ]);
           }

           if($s_responder->responder){
               dd($s_responder->responder);
           }


       }

    }

    function getTemplate($name){
        $templateData=[];
        $templateData['Appointment Reminder']='Hi [Name],
        this is a reminder for your appointment with [Company/Doctor] on [Date] at [Time].
        Please remember to bring any necessary documents.
        Reply "C" to confirm or "R" to reschedule. Thank you!';

        $templateData['Event Invitation']='Hey [Name], we\'re hosting an event on [Date] at [Venue].
        We\'d love for you to join us! RSVP by replying with "YES" or "NO". Looking forward to seeing you there!';

        $templateData['Product Promotion']='Hi [Name], great news! We\'re offering a special discount of [Percentage]%
         on [Product/Service] until [Date]. Don\'t miss out! Visit our website or store today.';

        $templateData['Feedback Request']='Hello [Name], we value your opinion! Please take a moment to share your feedback
         on your recent experience with us. Your insights help us improve. Click [Link] to complete the survey. Thanks!';

        $templateData['Payment Reminder']='This is a friendly reminder that your payment of [Amount] for invoice #[InvoiceNumber]
         is due on [DueDate]. Please make the payment at your earliest convenience. Thank you!';

        $templateData['Personal Celebration']='Happy Birthday, [Name]! 🎉 Wishing you a fantastic day filled with joy and laughter.
         May this year bring you all the success and happiness you deserve!';

        $templateData['General Greeting']='Hi [Name], we hope you\'re doing well! If you have any questions or need assistance,
        feel free to reach out. Have a great day!';

        return isset($templateData[$name])?$templateData[$name]:'N/A';
    }

    public function create(){

        $responders = [
            [
                'id'=>'1',
                'parent_id'=>null,
                'group'=>'2',
                'triggered_by_reply' => true,
                'triggered_by_click' => true,
                'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                'keyword' => 'STOP',
                'template' => $this->getTemplate('Feedback Request'),
                'child' => [
                    [
                        'id'=>'2',
                        'parent_id'=>'1',
                        'group'=>'3',
                        'triggered_by_reply' => true,
                        'triggered_by_click' => true,
                        'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                        'keyword' => 'STOP',
                        'template' => $this->getTemplate('Personal Celebration'),
                        'child' => [
                            [
                                'id'=>'4',
                                'parent_id'=>'2',
                                'group'=>'2',
                                'triggered_by_reply' => true,
                                'triggered_by_click' => true,
                                'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                                'keyword' => 'STOP',
                                'template' => $this->getTemplate('General Greeting'),
                                'child' => [
                                    [
                                        'id'=>'6',
                                        'parent_id'=>'4',
                                        'group'=>'2',
                                        'triggered_by_reply' => true,
                                        'triggered_by_click' => true,
                                        'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                                        'keyword' => 'STOP',
                                        'template' => $this->getTemplate('Product Promotion'),
                                        'child' => [
                                            [
                                                'id'=>'9',
                                                'parent_id'=>'6',
                                                'group'=>'2',
                                                'triggered_by_reply' => true,
                                                'triggered_by_click' => true,
                                                'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                                                'keyword' => 'STOP',
                                                'template' => $this->getTemplate('Event Invitation'),
                                            ]
                                        ]
                                    ],
                                    [
                                        'id'=>'7',
                                        'parent_id'=>'4',
                                        'group'=>'2',
                                        'triggered_by_reply' => true,
                                        'triggered_by_click' => true,
                                        'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                                        'keyword' => 'STOP',
                                        'template' => $this->getTemplate('Appointment Reminder'),
                                    ],
                                    [
                                        'id'=>'8',
                                        'parent_id'=>'4',
                                        'group'=>'2',
                                        'triggered_by_reply' => true,
                                        'triggered_by_click' => true,
                                        'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                                        'keyword' => 'STOP',
                                        'template' => $this->getTemplate('Personal Celebration'),
                                    ]
                                ]
                            ],
                            [
                                'id'=>'5',
                                'parent_id'=>'2',
                                'group'=>'2',
                                'triggered_by_reply' => true,
                                'triggered_by_click' => true,
                                'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                                'keyword' => 'STOP',
                                'template' => $this->getTemplate('Event Invitation'),
                            ]
                        ]
                    ],
                    [
                        'id'=>'3',
                        'parent_id'=>'1',
                        'triggered_by_reply' => true,
                        'triggered_by_click' => true,
                        'triggered_by_click_value' => 'https://webhook.site/67b6ad37-929d-40ad-a818-805dac11741f',
                        'keyword' => 'STOP',
                        'template' => $this->getTemplate('Event Invitation'),
                    ]
                ]
            ]
        ];

        $responders=collect($responders);

        $allResponders=Responder::get();

        $this->generateChildLoop($allResponders);

        dd('Complete');

        return view('customer.responder.create');
    }



}
