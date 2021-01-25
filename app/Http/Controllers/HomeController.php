<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
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

        return response("success",200);
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
