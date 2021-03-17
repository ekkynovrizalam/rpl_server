<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class SlackController extends Controller
{
    public function authToSlack()
    {
        $response = Http::get('https://slack.com/oauth/v2/authorize', [
            'client_id' => '1389727810467.1664912761461',
            'scope' => 'chat:write,commands,im:read,im:write,users.profile:read'
        ]);
// 
        return $response;
    }

    public function landing(Request $request)
    {

        $response = Http::asForm()->post('https://slack.com/api/oauth.v2.access', [
            'code' => $request['code'],
		'client_id' => '1389727810467.1664912761461',
		'client_secret' => '248a270b0c999ee455a5b6324ebf7831'
        ]);

        dd($response->body());

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

    public function regist(Request $request)
    {
        $response = Http::withToken(env('SLACK_TOKEN'))->withHeaders(['Accept'=>'application/x-www-form-urlencoded'])->get('https://slack.com/api/users.profile.get', [
            'user' => $request['user_id'],
        ]);

    	Log::debug($response->body()['profile']);

        $getData = $response->body()['profile'];

        if(strlen(  $getData['real_name']) == 20 )
        {
            if (str_contains($getData['real_name'], '_'))
            {
                $infoUserName = explode("_", $getData['real_name']);
                $realname = $getData['display_name'];
            }
            else
                return response("FORMAT NAMA PROFILE ANDA SALAH",200)->header('Content-Type', 'application/json');
        }
        else
            return response("FORMAT NAMA PROFILE ANDA SALAH",200)->header('Content-Type', 'application/json');

        try {
            DB::table('students')->insert([
                'nim' => $infoUserName[0],
                'kelas' => $infoUserName[1],
                'tim' => $infoUserName[2],
                'nama' => $realname
            ]);

            return response("Selamat anda sudah terdaftar,silahkan",200)->header('Content-Type', 'application/json');
        } catch (Throwable $e) {
            return response("ERROR :".$e,200)->header('Content-Type', 'application/json');
        }
    }

    public function report(Request $request)
    {
        $data = $request->all();

        $response = Http::withToken(env('SLACK_TOKEN'))->withHeaders(['Accept'=>'application/x-www-form-urlencoded'])->get('https://slack.com/api/users.profile.get', [
            'user' => $request['user_id'],
        ]);

        if (str_contains($data['text'], '|'))
        {
            $text = explode("|",$data['text']);
        }
        else
            return response("FORMAT LAPORAN ANDA ANDA SALAH",200)->header('Content-Type', 'application/json');


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
