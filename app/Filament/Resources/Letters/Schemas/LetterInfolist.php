<?php

namespace App\Filament\Resources\Letters\Schemas;

use App\Models\Letter;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LetterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->columnSpanFull()->schema([
                Section::make('The letter')->columnSpan(2)->schema([
                    TextEntry::make('child_first_name')->label('Child')
                        ->formatStateUsing(fn (Letter $r) => $r->child_first_name.($r->child_age ? ", {$r->child_age}" : '')),
                    TextEntry::make('town')->formatStateUsing(fn (Letter $r) => "{$r->town}, {$r->countryName()} {$r->countryFlag()}"),
                    TextEntry::make('body')->columnSpanFull()->prose()->placeholder('No text (picture letter)'),
                    ImageEntry::make('images')->label('Images')->columnSpanFull()->height(260)
                        ->state(fn (Letter $r) => $r->images->map(fn ($i) => $i->url(60))->all())
                        ->placeholder('No images')
                        ->helperText(fn (Letter $r) => $r->images->isEmpty() ? null : $r->images->map(fn ($i) => ($i->has_face ? 'Face detected. ' : '').($i->flagged ? 'FLAGGED. ' : '').(! $i->moderated ? 'Not auto-reviewed.' : 'Auto-reviewed.'))->implode(' | ')),
                ])->columns(2),
                Section::make('Status')->columnSpan(1)->schema([
                    TextEntry::make('reference')->copyable(),
                    TextEntry::make('source')->badge(),
                    TextEntry::make('payment_status')->badge(),
                    TextEntry::make('moderation_status')->badge(),
                    TextEntry::make('moderation_flags.auto')->label('Auto flags')->badge()->color('warning')->placeholder('None'),
                    TextEntry::make('moderation_flags.reviewed_by')->label('Reviewed by')->placeholder('Not yet'),
                    IconEntry::make('has_face')->boolean()->label('Face in image'),
                    IconEntry::make('wall_consent')->boolean()->label('Wall consent'),
                    IconEntry::make('instagram_consent')->boolean()->label('Instagram consent'),
                    TextEntry::make('consent_withdrawn_at')->dateTime()->placeholder('Not withdrawn'),
                    TextEntry::make('featured_at')->dateTime()->placeholder('Not featured'),
                ]),
            ]),
            Section::make('Delivery')->columns(4)->schema([
                TextEntry::make('parent_email')->copyable(),
                TextEntry::make('timezone'),
                TextEntry::make('paid_at')->dateTime()->placeholder('Unpaid'),
                TextEntry::make('elf_sent_at')->dateTime()->placeholder('Not sent'),
                TextEntry::make('santa_scheduled_for')->dateTime()->placeholder('Not scheduled')
                    ->helperText(fn (Letter $r) => $r->santa_scheduled_for ? 'Local: '.$r->santa_scheduled_for->timezone($r->timezone)->format('D j M Y g:i a') : null),
                TextEntry::make('santa_sent_at')->dateTime()->placeholder('Not sent'),
                TextEntry::make('santa_variant')->placeholder('Random at send time'),
                IconEntry::make('is_late')->boolean()->label('After cutoff'),
                TextEntry::make('event.name')->label('Party event')->placeholder('Individual'),
                TextEntry::make('payment.amountFormatted')->label('Payment')->placeholder('None')
                    ->state(fn (Letter $r) => $r->payment?->amountFormatted()),
                TextEntry::make('created_at')->dateTime(),
                TextEntry::make('deleted_at')->dateTime()->placeholder('Active'),
            ]),
        ]);
    }
}
