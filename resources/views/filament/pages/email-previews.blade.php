<x-filament-panels::page>
    <div x-data="{ template: 'elf' }" class="space-y-4">
        <div class="flex flex-wrap items-center gap-3">
            <label class="text-sm font-medium">Template</label>
            <select x-model="template" class="fi-input rounded-lg border-gray-300 text-sm dark:bg-gray-900 dark:border-gray-700">
                @foreach (\App\Filament\Pages\EmailPreviews::variantOptions() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <a :href="'{{ url('/admin-tools/email-preview') }}/' + template + '?pdf=1'" target="_blank" class="text-sm text-primary-600 underline" x-show="template !== 'elf'">Open PDF attachment</a>
        </div>
        <iframe :src="'{{ url('/admin-tools/email-preview') }}/' + template" class="w-full rounded-xl bg-white" style="height: 80vh; border: 1px solid #e5e7eb;"></iframe>
    </div>
</x-filament-panels::page>
