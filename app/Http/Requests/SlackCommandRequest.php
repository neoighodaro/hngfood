<?php

namespace HNG\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlackCommandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $validTokens = explode(',', env('SLACK_COMMAND_TOKENS'));

        return in_array($this->get('token'), $validTokens);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'team_id' => 'required',
            'user_id' => 'required',
            'token'   => 'required',
            'command' => 'required',
            'text'    => 'required',
        ];
    }
}
