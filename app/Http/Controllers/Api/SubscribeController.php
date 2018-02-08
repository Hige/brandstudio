<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use Debugbar;

class SubscribeController extends Controller
{
    public function check() {

    }

    public function create(Request $request)
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


        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:subscription_users,email,NULL,id,event_id,' . Input::get( 'event_id' ),
            'birthday' => 'date',
            'event' => 'required|integer',
            'gender' => 'in:m,f',
            'phone' => array('regex:/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'),
        ]);

        if($validator->fails()) {
            $response = Response::json( array(
                'status' => 'failed',
                'msg' => 'Неправильно заполнены данные формы.',
                'errors' =>  $validator->errors(),
            ) );

            return $response;
        }


        $subscribe = new SubscriptionUser;

        $subscribe->name = Input::get( 'name' );
        $subscribe->email = Input::get( 'email' );
        $subscribe->phone = Input::get( 'phone' );
        $subscribe->gender = Input::get( 'gender' );
        $subscribe->birthday = Input::get( 'birthday' );
        $subscribe->event_id = Input::get( 'event_id' );
        $subscribe->ip = $request->getClientIp();

        if($subscribe->save()) {
            $response = Response::json( array(
                'status' => 'success',
                'msg' => 'Подписка добавлена.',
                'subscribe' =>  $subscribe,
            ) );
        } else {
            $response = Response::json( array(
                'status' => 'failed',
                'msg' => 'Подписка не добавлена ошибка при сохранении.',
            ) );
        }

        return $response;
    }
}