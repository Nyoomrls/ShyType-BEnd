<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Block;
use App\Models\Matches;
use App\Models\Personality;
use Carbon\Carbon;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Termwind\Components\Dd;

class MessageController extends Controller
{
    public function send_message(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'sender' => ['required', 'integer'],
            'receiver' => ['required', 'integer'],
            'message' => ['required', 'string'],
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $message = new Message;

        $message->sender = $request->sender;
        $message->receiver = $request->receiver;
        $message->message = $request->message;

        if ($request->receiver > $request->sender) {
            $message->conversation_id = $request->receiver . $request->sender;
        } else {
            $message->conversation_id = $request->sender . $request->receiver;
        }

        $successMessage = $message->save();

        if ($successMessage) {
            // trigger pusher notification

            return [
                "message" => "Successfully registered",
                "status" => 201
            ];
        }
        return [
            'error' => 'Bad credentials',
            'status' => 401
        ];
    }



    public function get_conversation(Request $request)
    {
        if ($request->receiver > $request->sender) {
            $conversation_id = $request->receiver . $request->sender;
        } else {
            $conversation_id = $request->sender . $request->receiver;
        }

        // $messages = Message::where('conversation_id', $conversation_id)->get();
        $messages = Message::where('conversation_id', $conversation_id)->get();
        $user = User::where('id', $request->owner)->get();

        $structMessage = array();
        foreach ($messages as $key => $message) {
            array_push($structMessage, [
                'message' => $messages[$key]->message,
                'time' => Carbon::createFromTimeStamp(strtotime($messages[$key]->created_at))->diffForHumans(),
                'userid' => $messages[$key]->sender,
                'isRead' => $messages[$key]->isRead, // Add the isRead attribute here
            ]);
        }

        return ['status' => 'success', 'data' => $structMessage, 'user' => $user];
    }

    public function get_personalities(Request $request)
    {
        $senderData = Personality::where('user_id', $request->sender)->get();
        $receiverData = Personality::where('user_id', $request->receiver)->get();

        $matchedQuestions = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $senderQuestion = 'question' . $i;
            $receiverQuestion = 'question' . $i;
            
            $senderMatched = $senderData->where($senderQuestion, 1)->isNotEmpty();
            $receiverMatched = $receiverData->where($receiverQuestion, 1)->isNotEmpty();
            
            $matched = $senderMatched && $receiverMatched ? 1 : 0;
            
            $matchedQuestions[] = $matched;
            // $matchedQuestions = $receiverData;
        }
        return $matchedQuestions;
     
    
    }


    public function get_chats(Request $request)
    {
        $messageFrom = Message::where('sender', $request->sender)
            ->orwhere('receiver', $request->sender)
            ->orderBy('created_at', 'desc')
            ->get()->unique('conversation_id');

        $messageInbox = array();
        foreach ($messageFrom as $key => $message) {
            if ($request->sender != $message->sender) {
                $data = Block::where('blockerID', $request->sender)
                    ->where('blockedID', $message->sender)->get();

                if (sizeof($data) == 0 && $this->checkMatch($request->sender, $message->receiver)) {
                    $user = User::find($message->sender);
                    $messageFrom[$key]['user'] = $user;
                    array_push($messageInbox, $message);
                }
            } else if ($request->sender != $message->receiver) {
                $data = Block::where('blockerID', $request->sender)
                    ->where('blockedID', $message->receiver)->get();

                if (sizeof($data) == 0 && $this->checkMatch($request->sender, $message->receiver)) {
                    $user = User::find($message->receiver);
                    $messageFrom[$key]['user'] = $user;
                    array_push($messageInbox, $message);
                }
            }
        }

        return ['status' => 'success', 'data' => $messageInbox];
    }

    private function checkMatch($sender, $receiver)
    {
        return Matches::where('userID', $sender)
            ->where('matchUser', $receiver)
            ->exists();
    }


    public function get_blocks(Request $request)
    {
        $messageFrom = Message::where('sender', $request->sender)
            ->orwhere('receiver', $request->sender)
            ->orderBy('created_at', 'desc')
            ->get()->unique('conversation_id');

        $messageInbox = array();
        foreach ($messageFrom as $key => $message) {
            if ($request->sender != $message->sender) {
                $data = Block::where('blockerID', $request->sender)
                    ->where('blockedID', $message->sender)->get();

                if (sizeof($data) > 0) {
                    $user = User::find($message->sender);
                    $messageFrom[$key]['user'] = $user;
                    array_push($messageInbox, $message);
                }
            } else if ($request->sender != $message->receiver) {
                $data = Block::where('blockerID', $request->sender)
                    ->where('blockedID', $message->receiver)->get();

                if (sizeof($data) > 0) {
                    $user = User::find($message->receiver);
                    $messageFrom[$key]['user'] = $user;
                    array_push($messageInbox, $message);
                }
            }
        }

        return ['status' => 'success', 'data' => $messageInbox];
    }


    public function markAsRead(Request $request)
    {
        $sender = $request->input('sender');
        $receiver = $request->input('receiver');

        Message::where('sender', $receiver) // Only update messages where the receiver is the sender
            ->where('receiver', $sender)
            ->update(['isRead' => true]);

        return response()->json(['status' => 'success', 'message' => 'Conversation marked as read.']);
    }
}
