<?php

namespace App\Admin\Resources\TicketMessageResource\Pages;

use App\Admin\Resources\TicketMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketMessage extends EditRecord
{
    protected static string $resource = TicketMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
