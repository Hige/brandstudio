<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    public function list(Request $request)
    {
//        if(!$request->ajax()) {
//            return abort(404);
//        }

//        if ( Session::token() !== Input::get( '_token' ) ) {
//            return Response::json( array(
//                'status' => 'failed',
//                'msg' => 'Неавторизованная попытка использования API.'
//            ) );
//        }

        $events = Event::select()
            ->where('released', '1')
            ->whereDate('start', '<=', Carbon::today()->addDays(2))
            ->whereDate('start', '>=', Carbon::today())
            ->orderBy('id', 'asc')
//            ->get();
            ->pluck('title', 'id');


        $response = array(
            'status' => 'success',
            'msg' => 'Список событий на ближайшие два дня.',
            'events' => $events
        );

        return Response::json($response);
    }
}