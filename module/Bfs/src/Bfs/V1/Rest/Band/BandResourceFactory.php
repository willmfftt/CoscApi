<?php
namespace Bfs\V1\Rest\Band;

class BandResourceFactory
{
    public function __invoke($services)
    {
        return new BandResource();
    }
}
