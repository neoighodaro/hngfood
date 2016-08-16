<?php namespace HNG\Http\Requests;

use HNG\Lunch;
use HNG\Freelunch;

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
        $rules = [
            'free_lunch' => 'required|in:0,1',
            'buka_id'    => 'required|exists:bukas,id',
        ];

        // Get the buka ID expected from this order
        $buka_id = $this->input('buka_id');

        // Get all orders (array)
        $orders = (array) $this->get('orders');

        // Set validation rules for certain keys in each order in the array
        foreach ($orders as $key => $order) {
            $rules["orders.{$key}.note"]     = "between:1,255";
            $rules["orders.{$key}.servings"] = "required|numeric|between:1,5";
            $rules["orders.{$key}.id"]       = "required|exists:lunches,id,buka_id,{$buka_id}";
        }

        return $rules;
    }

    /**
     * Check if the request wants to redeem freelunches.
     *
     * @return boolean
     */
    public function wantsToRedeemFreelunch()
    {
        return $this->get('free_lunch') == 1;
    }

    /**
     * Checks if the user wallet cannot handle the order.
     *
     * @return boolean
     */
    private function userWalletCanHandleOrder()
    {
        $totalCost     = 0;
        $availableCash = number_unformat(auth()->user()->wallet);

        if ($this->wantsToRedeemFreelunch()) {
            $availableCash += (new Freelunch)->currentUserWorth();
        }

        $orders = (array) $this->get('orders');

        foreach ($orders as $order) {
            $lunch      = Lunch::find($order['id']);
            $totalCost += ($lunch->cost > 0 ? $lunch->cost : $order['cost']) * $order['servings'];
        }

        return $availableCash >= $totalCost;
    }
}
