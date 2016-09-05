<?php 
namespace HNG\Http\Requests;

class FreeLunchGiveOutRequest extends SlackCommandRequest
{
	public function getFreeLunchReason()
	{
		$reason = preg_replace('/\s*@\w+/', '', $this->slack('text'));

		return trim($reason);
	}

	public function getFreeLunchReceiver()
	{	
		preg_match('/\s*@\w+/', $this->slack('text'), $receiver);

		return array_get($receiver, 0);
	}
}
