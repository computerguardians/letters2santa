<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ElfArrivalMail;
use App\Mail\SantaLetterMail;
use App\Support\SampleLetter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EmailPreviewController extends Controller
{
    public function __invoke(Request $request, string $template)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $letter = SampleLetter::make();

        if ($template === 'elf') {
            return (new ElfArrivalMail($letter))->render();
        }

        if (preg_match('/^santa-(\d+)$/', $template, $m)) {
            $letter->santa_variant = (int) $m[1];
            $mail = new SantaLetterMail($letter);
            if ($request->boolean('pdf')) {
                return Pdf::loadView('pdf.santa-letter', $mail->templateData() + ['variant' => $mail->variant()])->setPaper('a4')->stream('santa-letter.pdf');
            }

            return $mail->render();
        }

        abort(404);
    }
}
