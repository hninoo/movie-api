<?php

namespace App\Repositories;
use App\Models\Movie;
use Illuminate\Support\Str;
use App\Models\Comment;

class MovieRepository extends BaseRepository
{
    public function __construct(Movie $model)
    {
        $this->model = $model;
        $this->perPage = config('enums.itemPerPage');
    }

    public function getMoviesList($authId){
        $movies = $this->model->where('author_id',$authId)->latest()->paginate($this->perPage);
        return $movies;
    }

    public function createComment($request)
    {
        return Comment::create([
            'movie_id' => $request->movieId,
            'email' => $request->email,
            'comment' => $request->comment,
        ]);
    }
}
