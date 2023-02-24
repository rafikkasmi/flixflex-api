<?php

namespace App\Http\Controllers\Api\Movies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Datasource\Tmdb;
use GuzzleHttp\Psr7\Response;


/**
 * @group Favorites Management
 *
 * APIs for adding, deleting and checking favorites shows and movies
 * @authenticated
 */
class FavoritesController extends Controller
{
    //BY CONVENTION FOR THE ITEM TYPE WE WILL PUT, 0=>MOVIES, 1=>TV SERIES


    public function addToFavorites(Request $request){
        $request->validate([
            "id"=>"required|integer"
        ]);

        if(DB::table('items_users')->where([
            "item_id"=>$request->id,
            "user_id"=>$request->user()->id,
        ])->first()){
            return response()->json(["message"=>"Item already in your favorites"]);          
        };

        DB::table('items_users')
    ->updateOrInsert(
        [
            "item_id"=>$request->id,
            "user_id"=>$request->user()->id,
            "item_type"=>$request->is('*favorites/movies') ? 0 : 1,
        ]
    );

        return response()->json(["success"=>"true","message"=>"Item added in your favorites successfully"]);          

    }

   

    public function deleteFromFavorites(Request $request,$id){

        if(!DB::table('items_users')->where([
            "item_id"=>$request->id,
            "user_id"=>$request->user()->id,
        ])->first()){
            return response()->json(["message"=>"Item doesnt exist in your favorites"]);          
        };

        DB::table('items_users')->where([
            "item_id"=>$request->id,
            "user_id"=>$request->user()->id,
        ])->delete();

        return response()->json(["success"=>"true","message"=>"Item removed from your favorites successfully"]);          

    }


    /**
     *
     * @queryParam page int Page you wanna get, Defaults to 1.
     * 
     */
    public function getFavorites(Request $request)
    {

        $page=$request->query("page");
        if(empty($page) || $page==0){
            $page=1;
        };
        //get 10 elements each time
        $favoriteMovies=DB::table('items_users')->where([
            "user_id"=>$request->user()->id,
            "item_type"=>$request->is('*favorites/movies') ? 0 : 1,
        ])->skip(($page-1)*10)->take(10)->get();

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.themoviedb.org/3',
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        $mediaType=$request->is('*favorites/movies') ? "movie" : "tv";
        $requests = function () use ($client,$favoriteMovies,$mediaType) {
            foreach ($favoriteMovies as $movie) {
                yield function() use ($client,$movie,$mediaType) {
                    return $client->getAsync(Tmdb::generateURL("$mediaType/$movie->item_id"));
                };
            }
            
        };
        $responses=[];
        $pool = new \GuzzleHttp\Pool($client, $requests(),[
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $index) use (&$responses) {
                if ($response->getStatusCode() == 200) {
                      $responses[] = json_decode($response->getBody()->getContents());
                }
            },
        ]);
    
        $pool->promise()->wait();
        return response()->json(["page"=>$page,"count"=>count($responses),"results"=>$responses]);


    }


}
