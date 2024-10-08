<?php

namespace App\Repositories\Api\Census;

use App\Models\Drafts;

class CensusRepository
{
    protected Drafts $draftsModel;

    public function __construct(Drafts $draftsModel)
    {
        $this->draftsModel = $draftsModel;
    }
}
