<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Pagination {

    protected int $perPage = 10;
    protected int $page = 1;

    /**
     * @param Request $request
     */
    public function setPaginationParams(Request $request): void {

        $this->perPage = $request->input('perPage', config('app.pagination.per_page'));
        $this->page = $request->input('page', config('app.pagination.page'));
    }
}
