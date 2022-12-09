<?php

namespace App\Service;

use App\Models\StoreQuestion;
use App\Models\StoreQuestionReply as StoreQuestionReplyModel;
use App\Mail\StoreQuestionReply;
use Illuminate\Support\Facades\Mail;
use App\Service\NotificationService;

class StoreQuestionService
{
    /**
     * create new store question
     *
     * @param array $request
     * @return StoreQuestion
     */
    public function create(array $request): StoreQuestion
    {
        $question = new StoreQuestion();

        $question->name = $request['name'];
        $question->email = $request['email'];
        $question->phone = $request['phone'];
        $question->message = $request['message'];

        $question->save();

        return $question;
    }

    /**
     * send store question reply
     *
     * @param array $request
     * @param StoreQuestion $question
     * @return string
     */
    public function sendReply(array $request, StoreQuestion $question, NotificationService $notificationService): string
    {
        $reply = new StoreQuestionReplyModel();

        $reply->store_question_id = $question->id;
        $reply->subject = $request['subject'];
        $reply->message = $request['message'];
        $reply->save();
        
//        $title = $request['subject'];
//        $description = $request['message'];
//        $customer_id = $question->customer_id;
//        $lang_id = 1;
//        $token = "fhLPtbbRTySYNWdy8jCD0j:APA91bF6tIHpstcEELekV8II1EbTynOhiG85I6jlXfjN-gGBu1mlyf2cD2smPGvT5JZLWRXppY1RXnhmEF8yZQYHQ6l3TIO44tbG1D3log8-qg-2qUErK7MnZQs1oGALTvH4MxvTX-4U";
//        $message = [
//            'title' => $title,
//            'body' => $description,
//        ];
//        
//        $notificationService->sendNotification($token, $message);

        Mail::to($question->email)->send(new StoreQuestionReply($request['message'], $request['subject']));
        return __('Email sent successfully.');
    }

    public function getAll($customerId = null)
    {
        $baseInquiry = StoreQuestion::with('replies');
        if ($customerId) {
            $baseInquiry = $baseInquiry->where('customer_id', $customerId);
        }
        return $baseInquiry->get();
    }
}
