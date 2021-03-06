<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\report;
use App\Models\student;
use Carbon\Carbon;
use DB;
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

        $data = json_decode($response->body());

	if($data->ok){
        try {
            DB::table('workspaces')->insert([
                'team_id' => $data->team->id,
                'token' => $data->access_token,
            ]);

            return view('landing');
        } catch (Throwable $e) {
            return $e;
        }
	}
else{
	return $data->error;
}
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
        $selectDataWorkspace = DB::table('workspaces')->where('team_id',$request['team_id'])->first();
        $token = $selectDataWorkspace->token;
        $response = Http::withToken($token)->withHeaders(['Accept'=>'application/x-www-form-urlencoded'])->get('https://slack.com/api/users.profile.get', [
            'user' => $request['user_id'],
        ]);

        $getData = json_decode($response->body())->profile;

        if(strlen(  $getData->real_name) == 22 )
        {
		Log::debug("real_name sesuai ".$getData->real_name);
            if (str_contains($getData->real_name, '_'))
            {
		Log::debug("format realname sesuai");
                $infoUserName = explode("_", $getData->real_name);
                $realname = $getData->display_name;
            }
            else
                return response("FORMAT NAMA PROFILE ANDA SALAH",200)->header('Content-Type', 'application/json');
        }
        else{
            Log::debug("format nama kepanjangan");
            return response("FORMAT NAMA PROFILE ANDA TIDAK SESUAI",200)->header('Content-Type', 'application/json');
        }
        try {
            $isStudentExist = student::where('user_id',$request['user_id'])->count();
            
            if($isStudentExist == 0){
                student::create([
                    'user_id' => $request['user_id'],
                    'nim' => $infoUserName[0],
                    'kelas' => $infoUserName[1],
                    'tim' => $infoUserName[2],
                    'nama' => $realname
                ]);
    
                return response("Selamat anda sudah terdaftar,silahkan",200)->header('Content-Type', 'application/json');
            }else{
                return response("Maaf, Anda sudah terdaftar. Registrasi hanya dilakukan 1 kali saja.",200)->header('Content-Type', 'application/json');
            }
            
        } catch (Throwable $e) {
            return response("ERROR :".$e,200)->header('Content-Type', 'application/json');
        }
    }

    public function report(Request $request)
    {
        $selectDataWorkspace = DB::table('workspaces')->where('team_id',$request['team_id'])->first();
        $token = $selectDataWorkspace->token;
        $data = $request->all();

        $isUserExist = DB::table('students')->where('user_id',$request['user_id'])->count();

        if($isUserExist > 0)
        {
            if (str_contains($data['text'], '|'))
            {
                $text = explode("|",$data['text']);
            }
            else
                return response("FORMAT LAPORAN ANDA ANDA SALAH",200)->header('Content-Type', 'application/json');
    
    
            $username = $data['user_id'];
            $yesterday = $text[0];
            $today = $text[1];
            $blocker = $text[2];

            if(report::where('created_at', '>=', Carbon::today())->where('user_id',$data['user_id'])->count() == 0 ){
                if(Carbon::now()->toTimeString() < "12:00:00"){
                    try {
                        report::create([
                            'user_id' => $data['user_id'],
                            'yesterday' => $yesterday,
                            'today' => $today,
                            'blocker' => $blocker,
                        ]);
            
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
                    } catch (Throwable $e) {
                        return response("ERROR :".$e,200)->header('Content-Type', 'application/json');
                    }
                }else{
                    return response("Mohon maaf anda sudah melewati batas waktu laporan hari ini",200)->header('Content-Type', 'application/json');
                }
                
            }else{
                return response("Mohon maaf anda sudah melakukan laporan hari ini",200)->header('Content-Type', 'application/json');
            }

            
        }else{
            return response("Mohon maaf anda belum terdaftar di database kami, silahkan melakukan registrasi trlebih dahulu",200)->header('Content-Type', 'application/json');
        }


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
