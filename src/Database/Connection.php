<?php

namespace Douyuxingchen\PhpLibraryStateful\Database;

use Douyuxingchen\PhpLibraryStateful\Database\Query\Builder;
use Illuminate\Database\Connection as BaseConnection;

class Connection extends BaseConnection {

    /**
     * Get a new query builder instance.
     *
     * @return Builder
     */
    public function query()
    {
        return new Builder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }

}