<?php

namespace App\Filament\Resources\ProdukPendukungs\Pages;

use App\Filament\Resources\ProdukPendukungs\ProdukPendukungResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProdukPendukungs extends ManageRecords
{
    protected static string $resource = ProdukPendukungResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
