<?php

namespace HNG\Http\Requests;

class WalletBalanceRequest extends SlackCommandRequest {

    public function getSlackText()
    {
        return $this->get('text');
    }

}
