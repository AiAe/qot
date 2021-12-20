<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class StaffController extends Controller
{

    protected array $staff = array(
        'organisers' => array(7, 26),
        'spreadsheeters' => array(293),
        'graphics' => array(),
        'mappoolers' => array(),
        'mappers' => array(170),
        'referees' => array(),
        'streamers' => array(5, 7104),
        'commentators' => array()
    );

    private function fetchStaff()
    {
        return Cache::rememberForever('staff', function () {
            $staff = [];

            foreach ($this->staff as $name => $type) {
                if (is_array($type) && count($type)) {
                    $prepareGet = "";
                    foreach ($type as $player) {
                        if (empty($prepareGet)) $prepareGet .= "?id=" . $player;
                        else $prepareGet .= "&id=" . $player;
                    }

                    $response = Http::get('https://api.quavergame.com/v1/users' . $prepareGet);

                    $staff[$name] = $response->json()['users'];
                }
            }

            return $staff;
        });
    }

    public function page()
    {
        $pageData[] = "";
        $pageData['seo']['title'] = "Staff";
        $pageData['seo']['description'] = "Official 4 Keys Tournament Registrations Open!";

        $pageData['staff'] = $this->fetchStaff();

        return view('staff.staff', $pageData);
    }

}
