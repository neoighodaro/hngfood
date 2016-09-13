<?php 
namespace HNG\Http\Controllers\SlackCommands;

use HNG\Freelunch;
use HNG\Traits\SlackResponse;
use HNG\Events\FreelunchQuotaUpdated;
use HNG\Http\Requests\SlackCommandRequest as Request;

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
	 * @param  Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function give(Request $request)
	{	
		$from 	= $request->slack('from');

		$to   	= $request->slack('to');

		$reason	= $request->slack('reason');

		$freelunch_quota	= $request->slack('freelunch_quota');

		if ((new Freelunch)->give($from->id, $to->id, $reason))
		{
			event(new FreelunchQuotaUpdated($freelunch_quota, $freelunch_quota - 1));

            return $this->slackResponse([
                'text'          => "Great! Free lunch alert!",
                'response_type' => 'in_channel',
                'attachments'   => [
                    'text' => "{$from->username} just gave {$to->username} a free lunch {$reason}"
                ]
            ]);
		}

        $msg = "Oops! Seems you messed up the commands, use */freelunch help* to see a list of commands you can run";

		return $this->slackResponse($msg);
	}	
}

