<?php
	
/*
	@ Harris Christiansen (Harris@HarrisChristiansen.com)
	2016-04-25
	Project: Members Tracking Portal
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use DB;
use Mail;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Member;

class BaseController extends Controller {
	
	/////////////////////////////// Home ///////////////////////////////
    
	public function getIndex(Request $request) {
		return view('pages.home');
	}
	
	/////////////////////////////// Authentication ///////////////////////////////
	
	public function isAuthenticated($request) {
		return $request->session()->get('authenticated_member') == "true";
	}
	
	public function isAdmin($request) {
		return $request->session()->get('authenticated_admin') == "true";
	}
	
	public function isSuperAdmin($request) {
		return $request->session()->get('authenticated_superAdmin') == "true";
	}
	
	public function getAuthenticated($request) {
		if ($this->isAuthenticated($request)) {
			return Member::find($this->getAuthenticatedID($request));
		}
		return null;
	}
	
	public function getAuthenticatedID($request) {
		if ($this->isAuthenticated($request)) {
			return $request->session()->get('member_id');
		}
		return null;
	}
	
	public function setAuthenticated(Request $request, $memberID, $memberName) {
		$request->session()->put('authenticated_member', 'true');
		$request->session()->put('member_id', $memberID);
		$request->session()->put('member_name', $memberName);
		$request->session()->flash('msg', "Welcome $memberName!");
	}

	/////////////////////////////// Helper Functions ///////////////////////////////
	
	public static function generateRandomInt() {
        srand();
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 9; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
	
	public function sendEmail($member, $subject, $msg) {
		if (true) {
			Mail::send('emails.default', ['member'=>$member, 'msg'=>$msg], function ($message) use ($member, $subject) {
				$message->from('purduehackers@gmail.com', 'Purdue Hackers');
				$message->to($member->email);
				$message->subject($subject);
			});
		}
	}
    
    static $twilioClient;
    public  function TwilioClient() {
	    if (null === static::$twilioClient) {
            static::$twilioClient = new Client(env("TWILIO_SID"), env("TWILIO_TOKEN"));
        }
        
        return static::$twilioClient;
    }
	
	public function sendSMS($member, $msg) {
		$msg = str_replace(["<br />","<br>"], ["",""], $msg);
		
		if (strlen($member->phone) > 7) {
			$phoneNum = preg_replace("/[^0-9]/", "", $member->phone);
			if (strlen($phoneNum) == 10) {
				$this->TwilioClient()->messages->create($phoneNum, ['from'=>'+17652312066', 'body'=>$msg]);
			}
		}
	}
    
}