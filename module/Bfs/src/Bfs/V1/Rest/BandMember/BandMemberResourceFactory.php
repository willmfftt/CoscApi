<?php
namespace Bfs\V1\Rest\BandMember;

class BandMemberResourceFactory
{
    public function __invoke($services)
    {
        return new BandMemberResource();
    }
}
