<?php 
namespace HNG\Http\Controllers\SlackCommands;

use HNG\Freelunch;
use HNG\Traits\SlackResponse;

class FreeLunchController extends Controller {

	use SlackResponse;

	/**
	 * FreeLunchController constructor.
	 */
	public function __construct()
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
		if ($done = (new Freelunch)->give($from->id, $to->id, $reason))
		{
            return $this->slackResponse([
                'text'          => "Great! Free lunch alert!",
                'response_type' => 'in_channel',
                'attachments'   => [
                    'text' => "{$from->username} just gave {$to->username} a free lunch for {$reason}"
                ]
            ]);
		}

        $msg = "Oops! Seems you messed up the commands, use */freelunch help* to see a list of commands you can run";

		return $this->slackResponse($msg);
	}	
}

