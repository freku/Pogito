<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Twitch\Helpers;
use App\Report;

class ModController extends Controller
{
    public function reports(Request $request)
    {
        $user_id = Helpers::getUserID();
        $page = $request->query('p') == null ? 1 : (int)$request->query('p');

        if (Helpers::hasRank($user_id, ['Mod', 'Admin'])) {
            $reports = Report::all()->where('checked', '0')->sortByDesc('created_at')->forPage($page, 5);
            $pages = [];
            $left = 1;
            $right = $page + 1;

            if ($page > 1) {
                $pages[] = $page - 1;
                $pages[] = $page;
                $pages[] = $page + 1;
                $left = $page - 1;
            } else {
                $pages[] = $page;
                $pages[] = $page + 1;
                $pages[] = $page + 2;
                $left = $page;
            }

            return view('mod.reports', ['reports' => $reports, 'page' => $page, 'pages' => $pages, 'left' => $left, 'right' => $right]);
        } else {
            
            return redirect('/');
        }
    }
}
