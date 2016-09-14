<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App;
use App\Http\Requests;
use App\Models\Contact;
use App\Models\History;
use \Nexmo\Client;

class DefaultController extends Controller
{
	/**
	* Function to return view for tab 1
	* @param Request $request the request object from client
	* @return html view with all contact detail
	*/
    public function tab1(Request $request)
    {
    	try {
    		$contacts = Contact::orderBy('id', 'desc')->get();
    		return view('tab1', compact(['contacts']));
    	} catch (\Exception $e) {
    		Log::critical('Error in DefaultController@tab1', ['message' => $e->getMessage()]);
    		App::abort(404);
    	}
    }

    /**
    * Function to create new contacts
    * @param Request $request the request object from client with form inputs
    * @return JSON response with success or failure status
    */
    public function createContact(Request $request)
    {
    	$json = [];
    	try {
    		$json['created'] = [];
    		$contacts = $request->contacts;
    		foreach ($contacts as $c) {
    			$contact = new Contact();
    			$contact->first_name = $c['first_name'];
    			$contact->last_name = $c['last_name'];
    			$contact->number = $c['number'];
    			$contact->save();
    			$json['created'][] = $contact;
    		}
    		$json['status'] = 1;
    	} catch (\Exception $e) {
    		Log::critical('Error in DefaultController@createContact', ['message' => $e->getMessage()]);
    		$json['status'] = 0;
    		$json['message'] = $e->getMessage();
    	}
    	return response()->json($json);
    }

    /**
    * Function to return view and data of single contact
    * @param integer $id the contact id from url
    * @param Request $request the request object from client
    * @return html view with single contact detail
    */
    public function viewContact($id, Request $request)
    {
    	try {
    		$contact = Contact::find($id);
    		if($contact) {
    			$histories = History::where('contact_id', $contact->id)->get();
    			return view('contact', compact(['contact', 'histories']));
    		}else {
    			App::abort(404);
    		}
    	} catch (\Exception $e) {
    		Log::critical('Error in DefaultController@viewContact', ['message' => $e->getMessage()]);
    		App::abort(404);
    	}
    }

    /**
    * Function to send OTP via sms
    * @param integer $id the contact id to whom OTP has to be sent
    * @param Request $request the request object from client with message details
    * @return JSON response with success or failure and message object
    */
    public function sendMessage($id, Request $request)
    {
    	$json = [];
    	try {
    		$contact = Contact::find($id);
    		if($contact) {
    			$nexmo = new Client(env('NEXMO_KEY'), env('NEXMO_SECRET'));
    			$number = str_replace('+', '', $contact->number);
    			$response = $nexmo->message->invoke('Kisan Network', $number, 'text', $request->message);
    			$status = $response['messages'][0];
    			$history = new History();
    			$history->contact_id = $contact->id;
    			$history->message = $request->message;
    			$history->nexmo_id = $status['message-id'];
    			$history->otp = $request->otp;
    			$history->status = $status['status'];
    			$history->save();
    			if($status['status'] != 0) {
    				throw new \Exception('Messaging servers failed.');
    			}
    			$json['history'] = $history;
    			$json['status'] = 1;
    		}else {
    			$kson['status'] = 0;
    		}
    	} catch (\Exception $e) {
    		Log::critical('Error in DefaultController@sendMessage', ['message' => $e->getMessage()]);
    		$json['status'] = 0;
    		$json['message'] = $e->getMessage();
    	}
    	return response()->json($json);
    }

    /**
    * Function to view all previous messages snet to any number
    * @param Request $request the request object from client
    * @return html view with all messages sent
    */
    public function history(Request $request)
    {
    	try {
    		$histories = History::join('contacts', 'contacts.id', '=', 'histories.contact_id')
    					->select(['histories.*', 'contacts.first_name', 'contacts.last_name', 'contacts.number'])
    					->orderBy('histories.id', 'desc')->get();
    		return view('history', compact(['histories']));
    	} catch (\Exception $e) {
    		Log::critical('Error in DefaultController@history', ['message' => $e->getMessage()]);
    		App::abort(404);
    	}
    }
}
