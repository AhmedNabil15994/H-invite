<?php

namespace Modules\Party\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Apps\Http\Controllers\Api\ApiController;
use Modules\Party\Repositories\Api\PartyRepository;
use Modules\Party\Transformers\Api\PartyResource;


class PartyController extends ApiController
{
    public function __construct(PartyRepository $party)
    {
        $this->party = $party;
    }
    public function index(Request $request) {
        $parties = $this->party->getAllActive('id','desc',$request);
        return $this->responsePaginationWithData(PartyResource::collection($parties));
    }

    public function show(Request $request,$id) {
        $party   = $this->party->findById($id);
        if(!$party){
            return $this->error(__('party::api.invalid_party'));
        }

        return $this->response( (new PartyResource($party))->jsonSerialize());
    }
}
