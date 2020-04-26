<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use App\Inn;
use App\Rules\InnRule;

class HandleInn extends Controller
{


    static private $fns_api_url = 'https://statusnpd.nalog.ru/api/v1/tracker/taxpayer_status';


    /**
     * Handle posted INN.
     *
     * @param  Request $request
     * @return View
     */
    public function handle_inn(Request $request)
    {


        //Проверка валидности ИНН
        $validatedData = $request->validate([
            'INN' => ['required',new InnRule],
        ]);

        $inn_input = $request->post()['INN'];

        //Проверяем есть ли запись в бд, и когда была внесена, если есть и проверка была раньше чем 24 часа назад, то берем из бд
        if($db_inn = $this->get_from_db_inn($inn_input)){
            return view('check_inn', ["response" => $db_inn]);
        }

        //При успешной проверке делаем запрос в ФНС
        $response = $this->request_to_fns($inn_input);

        //Пишем в бд и выводим данные
        if ($response->successful()) {
            $this->insert_to_db_inn($inn_input, $response->json()['message']);
            return view('check_inn', ["response" => $response->json()['message']]);
        } else {
            return view('check_inn')->withErrors(["custom_error" => $response->json()['message']]);
        }


    }

    /**
     * Create or update inn in db
     *
     * @param  Intger $inn_input
     * @param  String $response
     * @return void
     */

    private function insert_to_db_inn($inn_input,$response){

        $inn = Inn::firstOrNew(['INN' => $inn_input]);

        $inn->query_time = DB::raw('now()');
        $inn->response = $response;
        $inn->save();

    }

    /**
     * Get inn from db
     *
     * @param  Intger $inn_input
     * @return mixed
     */

    private function get_from_db_inn($inn_input){

        $inn = Inn::where('INN', $inn_input)->whereRaw('TIMESTAMPDIFF(HOUR, query_time,NOW()) < 24')->first();

        //Если такого инн нет в бд, идем дальше, если есть то возвращаем запись из бд
        if(!isset($inn)){
            return false;
        } else {
            return $inn->response;
        }

    }


    /**
     * Request to FNS
     *
     * @param  Int $inn
     * @return object
     */

    private function request_to_fns($inn){

        $post_data = [
            'inn' => $inn,
            'requestDate' => Carbon::createFromFormat('Y-m-d H:i:s', now())->format('Y-m-d'),
        ];

        // Http::fake();

        return Http::timeout(3)->post(self::$fns_api_url, $post_data);

    }

}