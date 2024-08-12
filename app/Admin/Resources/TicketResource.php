<?php

namespace App\Admin\Resources;

use App\Admin\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'ri-chat-3-line';

    public static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subject')
                    ->label('Subject')
                    ->columnSpan(function ($record) {
                        return $record ? 2 : 1;
                    })
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                        'replied' => 'Replied',
                    ])
                    ->columnSpan(function ($record) {
                        return $record ? 2 : 1;
                    })
                    ->default('open')
                    ->required(),
                Forms\Components\Select::make('priority')
                    ->label('Priority')
                    ->options([
                        'low' => 'Low',
                        'normal' => 'Normal',
                        'high' => 'High',
                    ])
                    ->columnSpan(function ($record) {
                        return $record ? 2 : 1;
                    })
                    ->default('normal')
                    ->required(),
                Forms\Components\Select::make('department')
                    ->label('Department')
                    ->options(config('settings.ticket_departments'))
                    ->columnSpan(function ($record) {
                        return $record ? 2 : 1;
                    }),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'id')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                    ->columnSpan(function ($record) {
                        return $record ? 2 : 1;
                    })
                    ->required(),
                Forms\Components\Select::make('assigned_to')
                    ->label('Assigned To')
                    ->relationship('user', 'id')
                    ->columnSpan(function ($record) {
                        return $record ? 2 : 1;
                    }),
                Forms\Components\Select::make('order_product_id')
                    ->label('Order Product')
                    ->relationship('orderProduct', 'id', function (Builder $query, Get $get) {
                        // Join orders and match the user_id
                        $query->join('orders', 'orders.id', '=', 'order_products.order_id')
                            ->where('orders.user_id', $get('user_id'));
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->product->name} - {ucfirst($record->status}")
                    ->columnSpan(function ($record) {
                        return $record ? 2 : 1;
                    })
                    ->disabled(fn (Get $get) => !$get('user_id')),
                Forms\Components\MarkdownEditor::make('message')
                    ->columnSpan(2)
                    ->label('Initial Message')
                    ->hiddenOn('edit')
                    ->columnSpan(function ($record) {
                        return $record ? 2 : 1;
                    })
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->badge(function ($record) {
                        return match ($record->status) {
                            'open' => 'success',
                            'closed' => 'danger',
                            'replied' => 'gray',
                        };
                    }),
                Tables\Columns\TextColumn::make('priority')
                    ->sortable()
                    ->badge(function ($record) {
                        return match ($record->priority) {
                            'low' => 'success',
                            'normal' => 'gray',
                            'high' => 'danger',
                        };
                    }),
                Tables\Columns\TextColumn::make('department')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
