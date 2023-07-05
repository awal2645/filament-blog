<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use Filament\Forms\Components\Card;
use Filament\Widgets\Widget;

class CustomerOverview extends Widget
{
    protected static string $view = 'filament.resources.customer-resource.widgets.customer-overview';

    protected function getCards(): array
    {
        return [
            Card::make('Unique views', '192.1k'),
            Card::make('Bounce rate', '21%'),
            Card::make('Average time on page', '3:12'),
        ];
    }
}
