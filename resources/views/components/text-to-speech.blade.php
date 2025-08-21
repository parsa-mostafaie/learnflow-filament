@props(['text' => ''])

@use(App\Helpers\LangHelper)

<span>
  @if (LangHelper::browserTTS())
    <livewire:speechable-text :text="$text" wire:key="tts_st_{{ md5($text) }}" />
  @else
    <livewire:piper-text :text="$text" wire:key="tts_pt_{{ md5($text) }}" />
  @endif
</span>
