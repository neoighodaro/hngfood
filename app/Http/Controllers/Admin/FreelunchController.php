<?php

namespace HNG\Http\Controllers\Admin;

use HNG\Freelunch;
use HNG\Http\Requests;
use HNG\Http\Controllers\Controller;
use HNG\Events\FreelunchQuotaUpdated;

class FreeLunchController extends Controller {

    /**
     * Display the free lunch overview.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview()
    {
        return view('admin.freelunch-overview', [
            'inPageTitle' => 'Free Lunch Overview',
            'freelunchOverview' => [
                'unused'    => Freelunch::activeAll()->count(),
                'remaining' => get_option('freelunch_quota', 0),
            ]
        ]);
    }

    /**
     * Update the free lunch quota.
     *
     * @param Requests\FreelunchUpdateRequest $request
     * @return array
     */
    public function update(Requests\FreelunchUpdateRequest $request)
    {
        $newQuota = $request->get('freelunch');
        $oldQuota = (int) get_option('freelunch_quota');

        if ($saved = add_option('freelunch_quota', $newQuota)) {
            event(new FreelunchQuotaUpdated($oldQuota, $newQuota));
        }

        return ['status' => ($saved ? 'success' : 'error')];
    }
}
