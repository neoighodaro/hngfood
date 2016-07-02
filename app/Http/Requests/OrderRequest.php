<?php namespace HNG\Http\Requests;

use HNG\Lunch;

class OrderRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user() && $this->userWalletCanHandleOrder();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = ['buka_id' => 'required|exists:bukas,id'];

        // Get the buka ID expected from this order
        $buka_id = $this->request->get('buka_id');

        // Get all orders (array)
        $orders = (array) $this->request->get('orders');

        // Set validation rules for certain keys in each order in the array
        foreach ($orders as $key => $order) {
            $rules["orders.{$key}.servings"] = 'required|numeric|between:1,5';
            $rules["orders.{$key}.id"] = "required|exists:lunches,id,buka_id,{$buka_id}";
        }

        return $rules;
    }

    /**
     * Checks if the user wallet cannot handle the order.
     *
     * @return bool
     */
    private function userWalletCanHandleOrder()
    {
        $totalCost     = 0;
        $availableCash = number_unformat(auth()->user()->wallet);

        $orders = (array) $this->request->get('orders');

        foreach ($orders as $order) {
            $lunch = Lunch::find($order['id']);
            $totalCost += $lunch->cost;
        }

        return $totalCost <= $availableCash;
    }
}
