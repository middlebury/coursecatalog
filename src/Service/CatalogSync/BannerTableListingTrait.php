<?php

namespace App\Service\CatalogSync;

/**
 * Provides access to a listing of Banner tables for sync.
 */
trait BannerTableListingTrait
{
    /**
     * Answer a list of the Banner tables.
     */
    protected function getBannerTables(): array
    {
        return [
            'GORINTG',
            'GTVDUNT',
            'GTVINSM',
            'GTVINTP',
            'GTVMTYP',
            'GTVSCHS',
            'SCBCRSE',
            'SCBDESC',
            'SCRATTR',
            'SCREQIV',
            'SCRLEVL',
            'SIRASGN',
            'SOBPTRM',
            'SSBDESC',
            'SSBSECT',
            'SSBXLST',
            'SSRATTR',
            'SSRBLCK',
            'SSRMEET',
            'SSRXLST',
            'STVACYR',
            'STVAPRV',
            'STVASTY',
            'STVATTR',
            'STVBLCK',
            'STVBLDG',
            'STVCAMP',
            'STVCIPC',
            'STVCOLL',
            'STVCOMT',
            'STVCSTA',
            'STVDEPT',
            'STVDIVS',
            'STVFCNT',
            'STVLEVL',
            'STVMEET',
            'STVPTRM',
            'STVPWAV',
            'STVREPS',
            'STVSCHD',
            'STVSUBJ',
            'STVTERM',
            'STVTRMT',
            'instructors',
        ];
    }
}
