<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyController extends Controller
{
    public function index()
    {
        
        // $result = $this->get_json_decoded('result');
        // $sorted = $this->get_json_decoded('sorted');
        
        $ahass = $this->ahass_arr();

        $data = $this->get_json_decoded('data-1');

        $arr = [];
        foreach($data['data'] as $k => $v){

            $arr[$k] = [
                'name'              => $v['name'],
                'email'             => $v['email'],
                'booking_number'    => $v['booking']['booking_number'],
                'book_date'         => $v['booking']['book_date'],
                'ahass_code'        => $v['booking']['workshop']['code'],
                'ahass_name'        => $v['booking']['workshop']['name'],
                'ahass_address'     => '',
                'ahass_contact'     => '',
                'ahass_distance'    => 0,
                'motorcycle_ut_code'=> $v['booking']['motorcycle']['ut_code'],
                'motorcycle'        => $v['booking']['motorcycle']['name']
            ];

            $ahass_code = array_column($ahass, 'ahass_code');
            $search = array_search($arr[$k]['ahass_code'],$ahass_code);
            if($search !== false){
                $arr[$k] = array_merge($arr[$k],$ahass[$search]);
            }
        }
 
        $distance = array_column($arr, 'ahass_distance');
        array_multisort($distance, SORT_ASC, $arr);
        
        return response()->json([
            'status' => 1,
            'message'=> 'Data Successfully Retrieved.',
            'data' => $arr
        ]);
    }

    private function ahass_arr()
    {   
        $data = $this->get_json_decoded('data-2');

        $arr = [];
        foreach($data['data'] as $k => $v){
            $arr[$k] = [
                'ahass_code'        => $v['code'],
                'ahass_name'        => $v['name'],
                'ahass_address'     => $v['address'],
                'ahass_contact'     => $v['phone_number'],
                'ahass_distance'    => $v['distance'],
            ];
        }

        return $arr;
    }

    private function get_json_decoded($filename)
    {
        $path = storage_path() . "/json/{$filename}.json";
        $json = json_decode(file_get_contents($path), true);

        return $json;
    }

}
