<?php

namespace App\Filament\Resources\Letters\Tables;

use App\Enums\LetterSource;
use App\Enums\ModerationStatus;
use App\Enums\PaymentStatus;
use App\Filament\Support\LetterActions;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class LettersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->with(['images', 'event']))
            ->columns([
                ImageColumn::make('thumb')->label('')
                    ->state(fn ($record) => $record->images->first()?->thumbUrl(60))
                    ->square()->size(44)->defaultImageUrl(null),
                TextColumn::make('child_first_name')->label('Child')->searchable()->weight('bold')
                    ->description(fn ($record) => "{$record->town}, {$record->countryName()} {$record->countryFlag()}"),
                TextColumn::make('reference')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('parent_email')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('source')->badge(),
                TextColumn::make('payment_status')->badge()->label('Payment'),
                TextColumn::make('moderation_status')->badge()->label('Moderation'),
                TextColumn::make('moderation_flags.auto')->label('Flags')->badge()->color('warning')->placeholder('—'),
                IconColumn::make('wall_consent')->boolean()->label('Wall')->trueIcon('heroicon-o-eye')->falseIcon('heroicon-o-eye-slash'),
                IconColumn::make('has_face')->boolean()->label('Face')->trueColor('danger')->falseColor('gray'),
                TextColumn::make('event.name')->label('Event')->toggleable()->placeholder('—'),
                TextColumn::make('santa_sent_at')->label('Santa sent')->since()->placeholder('—')->toggleable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('moderation_status')->options(ModerationStatus::class)->default(null),
                SelectFilter::make('payment_status')->options(PaymentStatus::class),
                SelectFilter::make('source')->options(LetterSource::class),
                TernaryFilter::make('wall_consent'),
                TernaryFilter::make('instagram_consent'),
                TernaryFilter::make('is_sample')->label('Sample data'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->label('Edit name/text'),
                    LetterActions::approve(),
                    LetterActions::reject(),
                    LetterActions::feature(),
                    LetterActions::resendElf(),
                    LetterActions::sendSantaNow(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve')->label('Approve selected')->icon('heroicon-o-check')->color('success')
                        ->requiresConfirmation()->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each(fn ($record) => LetterActions::setModeration($record, ModerationStatus::Approved))),
                    BulkAction::make('reject')->label('Reject selected')->icon('heroicon-o-x-mark')->color('danger')
                        ->requiresConfirmation()->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each(fn ($record) => LetterActions::setModeration($record, ModerationStatus::Rejected))),
                ]),
            ]);
    }
}
