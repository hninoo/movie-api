<?php


namespace App\Http\Resources;

trait BaseResourceCollection
{
    private $pagination;
    public function __construct($resource)
    {
        $this->pagination = [
            'lastPage'=>$resource->lastPage(),
            'currentPage' => $resource->currentPage(),
            'perPage' => $resource->perPage(),
            'totalItems' => $resource->total()
        ];
        parent::__construct($resource);
    }
}

