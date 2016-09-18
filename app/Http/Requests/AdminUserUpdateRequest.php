<?php

namespace HNG\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->ajax() && Gate::allows('users.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'   => 'required|exists:users,id',
            'role'      => 'permission:roles.manage|roleExists',
            'wallet'    => 'min:0|max:20000|permission:wallet.manage',
            'freelunch' => 'between:0,20|permission:free_lunch.manage',
        ];
    }
}
