<?php

namespace App\Filament\Resources\Events\Tables;

use App\Enums\EventStatus;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('business')->withCount(['letters' => fn ($q) => $q->settled()]))
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->description(fn (Event $r) => $r->town.', '.$r->country_code),
                TextColumn::make('business.name')->label('Host')->searchable()->sortable(),
                TextColumn::make('event_date')->date('j M Y')->sortable()->placeholder('—'),
                TextColumn::make('letters_count')->label('Letters')->sortable(),
                TextColumn::make('credits_remaining')->label('Credits left')->sortable()
                    ->description(fn (Event $r) => 'of '.number_format($r->credits_purchased)),
                TextColumn::make('status')->badge(),
                TextColumn::make('slug')->label('Party URL')->copyable()->copyableState(fn (Event $r) => $r->publicUrl())->limit(12)->toggleable(),
                TextColumn::make('created_at')->since()->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(EventStatus::class),
            ])
            ->recordActions([
                ViewAction::make(),
                self::adjustCredits(),
                self::setStatus(),
            ]);
    }

    /** Manual credit adjustment (goodwill, refunds, offline payment). Positive or negative. */
    public static function adjustCredits(): Action
    {
        return Action::make('adjustCredits')->label('Adjust credits')->icon('heroicon-o-ticket')->color('warning')
            ->schema([
                TextInput::make('credits')->numeric()->integer()->required()->minValue(-10000)->maxValue(10000)
                    ->helperText('Positive adds credits, negative removes them. Cannot take the balance below zero.'),
                TextInput::make('reason')->required()->maxLength(120),
            ])
            ->action(function (Event $record, array $data) {
                $delta = (int) $data['credits'];
                $ok = DB::transaction(function () use ($record, $delta, $data) {
                    $fresh = Event::whereKey($record->id)->lockForUpdate()->first();
                    if ($fresh->credits_remaining + $delta < 0) {
                        return false;
                    }
                    $fresh->credits_remaining += $delta;
                    if ($delta > 0) {
                        $fresh->credits_purchased += $delta;
                    }
                    $fresh->save();
                    \Illuminate\Support\Facades\Log::info("Admin credit adjustment on event {$fresh->id}: {$delta} ({$data['reason']}) by ".auth()->user()?->email);

                    return true;
                });
                $ok
                    ? Notification::make()->title('Credits adjusted')->success()->send()
                    : Notification::make()->title('Not enough credits to remove')->danger()->send();
            });
    }

    public static function setStatus(): Action
    {
        return Action::make('setStatus')->label('Change status')->icon('heroicon-o-arrow-path')
            ->schema([
                \Filament\Forms\Components\Select::make('status')->options(EventStatus::class)->required(),
            ])
            ->action(function (Event $record, array $data) {
                $record->update(['status' => $data['status']]);
                Notification::make()->title('Status updated')->success()->send();
            });
    }
}
