<?php

namespace App\Http\Resources;

use App\Models\Tag;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Comment;
use App\Http\Resources\TagResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'coverImage' => url($this->cover_image),
            'genres' => GenreResource::collection($this->genreData($this->genres)),
            'authorName' => $this->author->name,
            'tags' => TagResource::collection($this->tagsData($this->tags)),
            'imdbRatings' => $this->imdb_ratings,
            'pdfLink' => $this->pdf_link,
            'comments' => count($this->comment) ? CommentResource::collection($this->comment) : [],
            'relatedMovies' => count($this->getRelatedMovies($this->id)) > 0 ? MovieResource::collection($this->getRelatedMovies($this->id)) : []
        ];
    }
}
