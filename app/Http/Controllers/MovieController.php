<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MovieRequest;
use App\Http\Requests\MovieUpdateRequest;
use App\Http\Controllers\Controller;
use App\Repositories\MovieRepository;
use App\Http\Resources\MovieDetailResource;
use App\Http\Utilities\HttpUtility;
use App\Http\Resources\MovieResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Exceptions\NotFoundException;

class MovieController extends Controller
{
    public function __construct(MovieRepository $movieRepo, HttpUtility $httpUtility)
    {
        $this->movieRepo = $movieRepo;
        $this->httpUtility = $httpUtility;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $movies = $this->movieRepo->getMoviesList(Auth::user('api')->id);
       return $this->httpUtility->successResponse(new MovieResourceCollection($movies));
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieRequest $request)
    {
        $input = [];
        if($request->hasFile('coverImage')){
            $imageName = time().'.'.$request->coverImage->extension();  
            $request->coverImage->move(public_path('images/movie'), $imageName);
            $input['cover_image'] = $imageName; 
        }
        $input['title'] = $request->title;
        $input['summary'] = $request->summary;
        $input['author_id'] = Auth::user('api')->id;
        $input['imdb_ratings'] = $request->imdbRatings;
        $input['pdf_link'] = $request->pdfLink;
        $input['genres'] = json_encode($request->genreIds);
        $input['tags'] = json_encode($request->tagIds);

        $movie = $this->movieRepo->create($input);

        return $this->httpUtility->createResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->movieRepo->getById($id);
        if(!$data){
            throw new NotFoundException('ID not found');
        }
        return $this->httpUtility->successResponse(new MovieDetailResource($data));
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieUpdateRequest $request, string $id)
    {
        if(!$this->movieRepo->getById($id)){
            throw new NotFoundException('ID not found');
        }
        $input = [];
        if($request->hasFile('coverImage')){
            $imageName = time().'.'.$request->coverImage->extension();  
            $request->coverImage->move(public_path('images/movie'), $imageName);
            $input['cover_image'] = $imageName; 
        }
        $input['title'] = $request->title;
        $input['summary'] = $request->summary;
        $input['author_id'] = Auth::user('api')->id;
        $input['imdb_ratings'] = $request->imdbRatings;
        $input['pdf_link'] = $request->pdfLink;
        $input['genres'] = json_encode($request->genreIds);
        $input['tags'] = json_encode($request->tagIds);

        $this->movieRepo->update($input,$id);
        return $this->httpUtility->updateResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->movieRepo->delete($id);
        return $this->httpUtility->deleteResponse();
    }

    /**
     * Comment Create
     */

    public function createComment(CommentRequest $request){

        $this->movieRepo->createComment($request);

        return $this->httpUtility->successResponse();
    }

     /**
     * Movie List Without Auth
     */

    public function movieListByNoAuth()
    {
        $movies = $this->movieRepo->getPaginated();
       return $this->httpUtility->successResponse(new MovieResourceCollection($movies));
    }
}
