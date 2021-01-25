<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class SlackController extends Controller
{
    public function authToSlack()
    {
        $response = Http::get('https://slack.com/oauth/authorize', [
            'scope' => 'chat:write:bot,commands,im:read,im:write,users.profile:read',
            'client_id' => '1389727810467.1664912761461'
        ]);
// 

        return $response;
    }

    public function landing()
    {
        return view('landing');
    }

    public function event()
    {
        return response('home',200);
    }
    
    public function push_event(Request $request)
    {
        $data = $request->all();

        Log::debug(json_encode($data));

        return response($data, 200);
    }

    public function help(Request $request)
    {
        $data = $request->all();

        Log::debug('HELP DEBUG: '.json_encode($data));

        $message = "
            {
                'blocks': [
                    {
                        'type': 'section',
                        'text': {
                            'type': 'mrkdwn',
                            'text': 'Hai :wave: , RPL Bot membantu anda.'
                        }
                    },
                    {
                        'type': 'section',
                        'block_id': 'section789',
                        'fields': [
                            {
                                'type': 'mrkdwn',
                                'text': '*Command List:*\n1. `/help` : List Bantuan\n2. `/report` : Submit Daily Report'
                            }
                        ]
                    }
                ]
            }
        ";

        return response($message,200)->header('Content-Type', 'application/json');
    }

    public function report(Request $request)
    {
        $data = $request->all();

        Log::debug('HELP DEBUG: '.json_encode($data));

        $text = explode("|",$data['text']);

        $username = $data['user_name'];
        $yesterday = $text[0];
        $today = $text[1];
        $blocker = $text[2];

        $message = "
            {
                'blocks': [
                    {
                        'type': 'header',
                        'text': {
                            'type': 'plain_text',
                            'text': 'Rekap Report @".$username."',
                            'emoji': true
                        }
                    },
                    {
                        'type': 'divider'
                    },
                    {
                        'type': 'section',
                        'text': {
                            'type': 'mrkdwn',
                            'text': ':dart: *Yang Dilakukan*\n ".$today."'
                        }
                    },
                    {
                        'type': 'section',
                        'text': {
                            'type': 'mrkdwn',
                            'text': ':clock730: *Yang Sudah dilakukan*\n ".$yesterday."'
                        }
                    },
                    {
                        'type': 'section',
                        'text': {
                            'type': 'mrkdwn',
                            'text': ':negative_squared_cross_mark: *Hambatan*\n ".$blocker."'
                        }
                    },
                    {
                        'type': 'divider'
                    },
                    {
                        'type': 'section',
                        'text': {
                            'type': 'mrkdwn',
                            'text': '*Pastikan Anda selalu melakukan daily report dari senin-jumat* Development by <novriza.com|ENA>'
                        }
                    }
                ]
            }
        ";

        return response($message,200)->header('Content-Type', 'application/json');
    }

    public function replay_message($message,$channel)
    {
        $response = Http::post('https://slack.com/api/chat.postMessage', [
            'channel' => 'Steve',
            'role' => 'Network Administrator',
        ]);

        return true;
    }
}
