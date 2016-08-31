<?php 
namespace HNG\Http\Requests;

class FreeLunchGiveoutRequest extends SlackCommandRequest
{
	public function getFreeLunchReason()
	{
		$reason = preg_replace('/\s*@\w+/', '', $this->get('text'));

		return trim($reason);
	}

	public function getFreeLunchReceiver()
	{
		preg_match('/\s*@\w+/', $this->get('text'), $receiver);

		return array_get($receiver,0);
	}
}

?>