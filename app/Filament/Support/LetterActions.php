<?php

namespace App\Filament\Support;

use App\Enums\ModerationStatus;
use App\Jobs\SendElfEmail;
use App\Jobs\SendSantaEmail;
use App\Models\Letter;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

/** Shared admin actions for letters (table rows and the view page). */
class LetterActions
{
    public static function setModeration(Letter $letter, ModerationStatus $status): void
    {
        $flags = $letter->moderation_flags ?? [];
        $flags['reviewed_by'] = auth()->user()?->email;
        $flags['reviewed_at'] = now()->toIso8601String();
        $letter->forceFill(['moderation_status' => $status, 'moderation_flags' => $flags])->save();
    }

    public static function approve(): Action
    {
        return Action::make('approve')->icon('heroicon-o-check-circle')->color('success')
            ->visible(fn (Letter $r) => $r->moderation_status !== ModerationStatus::Approved)
            ->action(function (Letter $record) {
                self::setModeration($record, ModerationStatus::Approved);
                Notification::make()->title('Letter approved')->success()->send();
            });
    }

    public static function reject(): Action
    {
        return Action::make('reject')->icon('heroicon-o-x-circle')->color('danger')->requiresConfirmation()
            ->modalDescription('The family still receives the Elf and Santa emails. The letter just never appears publicly.')
            ->visible(fn (Letter $r) => $r->moderation_status !== ModerationStatus::Rejected)
            ->action(function (Letter $record) {
                self::setModeration($record, ModerationStatus::Rejected);
                Notification::make()->title('Letter rejected (emails still go out)')->warning()->send();
            });
    }

    public static function feature(): Action
    {
        return Action::make('feature')->label(fn (Letter $r) => $r->featured_at ? 'Unfeature' : 'Feature (Top Letters)')
            ->icon('heroicon-o-star')->color('warning')
            // Only with Instagram consent, approved, settled and no detected face.
            ->visible(fn (Letter $r) => $r->instagram_consent && ! $r->consent_withdrawn_at
                && $r->moderation_status === ModerationStatus::Approved && $r->isSettled() && ! $r->has_face)
            ->action(function (Letter $record) {
                $record->forceFill(['featured_at' => $record->featured_at ? null : now()])->save();
                Notification::make()->title($record->featured_at ? 'Featured in Top Letters' : 'Removed from Top Letters')->success()->send();
            });
    }

    public static function resendElf(): Action
    {
        return Action::make('resendElf')->label('Resend Elf email')->icon('heroicon-o-paper-airplane')->requiresConfirmation()
            ->visible(fn (Letter $r) => $r->isSettled())
            ->action(function (Letter $record) {
                $record->forceFill(['elf_sent_at' => null])->save();
                SendElfEmail::dispatch($record)->onQueue('emails');
                Notification::make()->title('Elf email queued')->success()->send();
            });
    }

    public static function sendSantaNow(): Action
    {
        return Action::make('sendSantaNow')->label(fn (Letter $r) => $r->santa_sent_at ? 'Resend Santa email' : 'Send Santa email now')
            ->icon('heroicon-o-gift')->color('danger')->requiresConfirmation()
            ->modalDescription('Sends the Santa email immediately regardless of the schedule.')
            ->visible(fn (Letter $r) => $r->isSettled())
            ->action(function (Letter $record) {
                $record->forceFill(['santa_sent_at' => null, 'santa_dispatched_at' => now()])->save();
                SendSantaEmail::dispatch($record)->onQueue('santa');
                Notification::make()->title('Santa email queued')->success()->send();
            });
    }
}
