<?php

namespace App\Http\Controllers\Api\Movies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Datasource\Tmdb;


/**
 * @group Movies and Shows Routes
 *
 * APIs for getting movies and shows' datas.
 * @authenticated
 */
class MovieController extends Controller
{
    
    /**
     * Get All Movies
     *
     * This endpoint lets you get all movies in batches of 10.
     * @queryParam page int Page you wanna get, Defaults to 1.
     */
    public function getAllMovies(Request $request)
    {
    $page=$request->query("page");
    if(empty($page) || $page==0){
        $page=1;
    };
    $items=Tmdb::getAllItems("movie",$page);
    if(!$items)return response(["error"=>"Unable to process"], 422);
    return response()->json(["page"=>$page,"count"=>count($items),"results"=>$items]);
    }

    /**
     * Get All Tv Series
     *
     * This endpoint lets you get all tv series in batches of 10.
     * @queryParam page int Page you wanna get, Defaults to 1.
     */
    public function getAllTvSeries(Request $request)
    {
    $page=$request->query("page");
    if(empty($page) || $page==0){
        $page=1;
    };
    $items=Tmdb::getAllItems("tv",$page);
    if(!$items)return response(["error"=>"Unable to process"], 422);
    return response()->json(["page"=>$page,"count"=>count($items),"results"=>$items]);
    }

    /**
     * Get Popular Movies
     *
     * This endpoint lets you get top 5 rated movies.
     */
    public function getPopularMovies(Request $request)
    {
    $items=Tmdb::getMostPopularItems("movie");
    if(!$items)return response(["error"=>"Unable to process"], 422);
    return response()->json(["count"=>count($items),"results"=>$items]);
    }

    /**
     * Get Popular Tv Series
     *
     * This endpoint lets you get top 5 rated tv series.
     */
    public function getPopularTvSeries(Request $request)
    {
    $items=Tmdb::getMostPopularItems("tv");
    if(!$items)return response(["error"=>"Unable to process"], 422);
    return response()->json(["count"=>count($items),"results"=>$items]);
    }


     /**
     * Get a Movie
     *
     * This endpoint lets you get a movie by it's id.
     */
    public function getMovie(Request $request,$id)
    {
    
    $items=Tmdb::getItem("movie",$id);
    if(!$items)return response(["error"=>"Item doesn't exists"], 422);
    return response()->json(["results"=>$items]);
    }

    /**
     * Get a Tv Serie
     *
     * This endpoint lets you get a tv serie by it's id.
     */
    public function getTvSerie(Request $request,$id)
    {

    $items=Tmdb::getItem("tv",$id);
    if(!$items)return response(["error"=>"Item doesn't exists"], 422);
    return response()->json(["results"=>$items]);
    }

    
     /**
     * Search a Movie
     *
     * This endpoint lets you search a movie.
     * @queryParam page int Page you wanna get, Defaults to 1.
     * 
     */
    public function searchMovies(Request $request,$query)
    {
    $page=$request->query("page");
    if(empty($page) || $page==0){
        $page=1;
    };
    $items=Tmdb::searchItems("movie",$query,$page);
    if(!$items)return response(["error"=>"Item doesn't exists"], 422);
    return response()->json(["results"=>$items]);

    }

    /**
     * Search a Tv Serie
     *
     * This endpoint lets you search a tv serie.
     * @queryParam page int Page you wanna get, Defaults to 1.
     * 
     */
    public function searchTvSeries(Request $request,$query)
    {
    $page=$request->query("page");
    if(empty($page) || $page==0){
        $page=1;
    };
    $items=Tmdb::searchItems("tv",$query,$page);
    if(!$items)return response(["error"=>"Item doesn't exists"], 422);
    return response()->json(["results"=>$items]);
    }

    
    /**
     * Get a Movie trailer
     *
     * This endpoint lets you get a movie's trailer by its Id.
     * 
     */
     public function getMovieTrailer(Request $request,$id)
    {
    
    $items=Tmdb::getItemTrailer("movie",$id);
    if(!$items)return response(["error"=>"Item doesn't exists"], 422);
    return response()->json(["results"=>$items]);
    }

    /**
     * Get a Tv Serie trailer
     *
     * This endpoint lets you get a tv serie's trailer by its Id.
     * 
     */
    public function getTvSerieTrailer(Request $request,$id)
    {

    $items=Tmdb::getItemTrailer("tv",$id);
    if(!$items)return response(["error"=>"Item doesn't exists"], 422);
    return response()->json(["results"=>$items]);
    }


}
