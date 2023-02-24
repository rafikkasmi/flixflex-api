<?php
namespace App\Http\Datasource;

use Illuminate\Support\Facades\Http;

class Tmdb{
    public static function generateURL($core){
        return "https://api.themoviedb.org/3/$core?api_key=".env("TMDB_API_KEY",'');
    }

    
    static function getAllItems($mediaType ,$page){
        $elementsPage= intdiv($page,2) + $page % 2;
        $response = Http::get(self::generateURL("discover/$mediaType")."&sort_by=first_air_date.desc&page=$elementsPage");
        if(!$response->ok()){
            return false;
        }
        $range= ($page%2)==1 ? [0,10] : [10,20];
        $items = array_slice($response->json()["results"],$range[0],$range[1]);
        return $items;
    }

    static function getMostPopularItems($mediaType){
        $response = Http::get(self::generateURL("$mediaType/top_rated"));
        if(!$response->ok()){
            return false;
        }
        $items = array_slice($response->json()["results"],0,5);
        return $items;
    }

    static function getItem($mediaType ,$id){
        $response = Http::get(self::generateURL("$mediaType/$id"));
        if(!$response->ok()){
            return false;
        }
        return $response->json();
    }

    static function searchItems($mediaType,$query,$page){
        $response = Http::get(self::generateURL("search/$mediaType")."&query=$query&page=$page");
        if(!$response->ok()){
            return false;
        }
        return $response->json();
    }

    static function getItemTrailer($mediaType ,$id){
        $response = Http::get(self::generateURL("$mediaType/$id/videos"));
        if(!$response->ok()){
            return false;
        }
        return $response->json();
    }
}   