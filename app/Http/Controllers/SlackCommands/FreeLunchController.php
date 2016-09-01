<?php 
namespace HNG\Http\Controllers\SlackCommands;

use HNG\User;
use HNG\Freelunch;
use HNG\Traits\SlackResponse;
use HNG\Http\Requests\FreeLunchGiveoutRequest as Request;

class FreeLunchController extends Controller 
{	

	use SlackResponse;

	function __construct()
	{
		$this->middleware(['SlackUserExists', 'verifyFreeLunchRequest']);
	}

	/**
	* Give free lunch to a user
	* 
	* @param $from
	* @param $to
	* @param $reason
    * @return \Illuminate\Http\Response
	*/
	public function give($from, $to, $reason)
	{
		if($done = (new Freelunch)->give($from->id, $to->id, $reason))
		{
			$message = "Free lunch alert!"

			$attachments[]['text'] = "{$from->username} just gave {$to->username} a free lunch for {$reason}";
			return $this->slackResponse($message, $attachments, false);
		}

		return $this->slackResponse("Oops! Seems you messed up the commands, use /freelunch help to see a list of commands you can run");		
	}	
}

