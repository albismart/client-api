<?php

namespace App\Http\Controllers;

use TrayLabs\InfluxDB\Facades\InfluxDB;

class InfluxController extends Controller
{


    public static function read($query)
    {
        // executing a query will yield a resultset object
        $result = InfluxDB::query($query);

        // get the points from the resultset yields an array
        return $result->getPoints();
    }


    public static function write($data, $measurement)
    {
        $points=[];
        foreach($data as $key => $signal){
            $point = new InfluxDB\Point(
        $measurement, // name of the measurement
        null, // the measurement value
        ['id' => $key], // optional tags
        $signal,  // optional additional fields
        time());

            array_push($points, $point);
        }

        return InfluxDB::writePoints($points, \InfluxDB\Database::PRECISION_SECONDS);
    }
}
