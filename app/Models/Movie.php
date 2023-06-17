<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $table = 'movies';
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }

    public function comment()
    {
       return $this->hasMany(Comment::class);
    }

    public function getRelatedMovies($id)
    {
        $currentMovie = Movie::findOrFail($id);
        $relatedMovies = Movie::query()
        ->where('id', '!=', $id)
        ->orderBy('imdb_ratings', 'desc') 
        ->take(7)
        ->get();

        $relatedMovies = $relatedMovies->filter(function ($movie) use ($currentMovie) {
            $relatedGenres = json_decode($movie->genres);
            $relatedTags = json_decode($movie->tags);
        
            $currentGenres = json_decode($currentMovie->genres);
            $currentTags = json_decode($currentMovie->tags);
        
            $hasGenreOverlap = count(array_intersect($currentGenres, $relatedGenres)) > 0;
            $hasTagOverlap = count(array_intersect($currentTags, $relatedTags)) > 0;
        
            return $hasGenreOverlap || $hasTagOverlap;
        });

        return $relatedMovies;
    }
    public function genreData($genreIds)
    {
       return Genre::whereIn('id', json_decode($genreIds))->get();  
    }

    public function tagsData($tagIds)
    {
        return Tag::whereIn('id', json_decode($tagIds))->get();  
    }
}
