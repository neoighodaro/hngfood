<?php 
namespace HNG\Http\Controllers\SlackCommands;

use HNG\User;
use HNG\Freelunch;
use HNG\Http\Requests\SlackCommandRequest as Request;

class ClassName extends AnotherClass
{	
	private $allowed_giver;

	function __construct()
	{
		$this->allowed_giver = User::SUPERUSER;

		$this->middleware(['SlackUserExists', 'verifySlackRequest']);
	}

	/**
	* Give free lunch to a user
	* 
	* @param Request $request
    * @return \Illuminate\Http\Response
	*/
	public function give(Request $request)
	{
		//verify that user has authority to give free lunch
		// verify that receiver is a valid user
		// by all means give the man's man/girl a free lunch :D

		$giver = User::whereSlackId($request->get('user_id'))->role($this->allowed_giver)->first();

		if(!$giver) {
			$message = "Ahan! Why are you lying?";

			$attachments[]['text'] = "Bet you know you can not give free lunch";

			return $this->slackResponse($message, $attachments);
		}

		$request_details = $request->get('text', '');

		//[info] take out receiver from text

		preg_match('/\s*@\w+/', $request_details, $receiver);

		$lunch_receiver = array_get($receiver, 0);		

		if(!$request_details || !$lunch_receiver) {
			$message = "Hmmmm";

			$attachments[]['text'] = "Will you not say who the free lunch is for?";

			return $this->slackResponse($message, $attachments);
		}

		if(!$receiver = User::whereUsername(str_replace('@', '', $lunch_receiver))->first()) {
			$message = "Free Lunch is for humans only!";

			$attachments[]['text'] = "{$lunch_receiver} does not exist :face_with_rolling_eyes: ";

			return $this->slackResponse($message, $attachments);
		}

		//[info] get reason for free lunch
		$reason = trim(str_replace("{lunch_receiver}", '', $request_details));

		$done   = Freelunch::give($giver->id, $receiver->id, $reason);

		if($done)
		{
			$message = "Free lunch alert!"

			$attachments[]['text'] = "{$giver->username} just gave {$lunch_receiver} a free lunch for {$reason}";
			return $this->slackResponse($message, $attachments, false);
		}

		return $this->slackResponse("Well this sucks!","The free lunch wasn't given");
		
	}	
}

?>