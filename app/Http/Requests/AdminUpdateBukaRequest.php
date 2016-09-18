<?php

namespace HNG\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateBukaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->ajax() && Gate::allows('inventory.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|unique:bukas',
            'base_cost' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get the request data in the format we really want.
     *
     * @return array
     */
    public function data()
    {
        return [
            'name' => $this->get('name'),
            'base_cost' => (float) $this->get('base_cost'),
        ];
    }
}
