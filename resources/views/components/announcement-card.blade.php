{{-- resources/views/components/announcement-card.blade.php --}}
{{--
  A small, reusable card for a single announcement.
  Drop this file in: resources/views/components/announcement-card.blade.php

  Usage:
    <x-announcement-card
      :title="$a['title']"
      :href="$a['link'] ?? null"
      :course="$a['courseName'] ?? null"
      :category="$a['category'] ?? null"
      :author="$a['author'] ?? null"
      :published="$a['publishedAt'] ?? null"
      :preview="$preview"
      :html="$a['description'] ?? null"
      class="shadow-sm" />
--}}

@props([
    'title' => '',
    'href' => null,
    'course' => null,
    'author' => null,
    'category' => null,
    'published' => null, // display string, e.g. "21/10/2025 10:12"
    'html' => null, // full HTML of the announcement body
    'preview' => null, // short plain-text preview
])

<article
    {{ $attributes->merge([
        'class' => 'rounded-2xl border border-slate-800 bg-slate-900/40 p-4 transition hover:border-slate-700',
    ]) }}>
    <header class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <h3 class="text-sm font-medium leading-6 text-slate-200 truncate">
                @if ($href)
                    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer"
                        class="hover:underline">{{ $title }}</a>
                @else
                    {{ $title }}
                @endif
            </h3>
            <div class="mt-1 text-xs text-slate-400 flex flex-wrap items-center gap-x-2 gap-y-1">
                @if ($course)
                    <span class="font-medium text-slate-300">{{ $course }}</span>
                @endif
                @if ($category)
                    <span aria-hidden="true">·</span><span>{{ $category }}</span>
                @endif
                @if ($author)
                    <span aria-hidden="true">·</span><span>{{ $author }}</span>
                @endif
                @if ($published)
                    <span aria-hidden="true">·</span><time>{{ $published }}</time>
                @endif
            </div>
        </div>
    </header>

    <div class="mt-3">
        <details class="group">
            <summary class="list-none cursor-pointer select-none text-sm leading-relaxed text-slate-300/90">
                {{-- Preview (collapsed). Keep it readable without Tailwind line-clamp plugin. --}}
                <span class="inline-block align-top"
                    style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{{ $preview }}</span>
                <span class="ml-1 text-slate-400 group-open:hidden">Ler mais</span>
                <span class="ml-1 text-slate-400 hidden group-open:inline">Mostrar menos</span>
            </summary>
            @if ($html)
                <div class="mt-3 prose prose-sm max-w-none text-slate-300">
                    {!! $html !!}
                </div>
            @endif
        </details>
    </div>
</article>



{{-- resources/views/dashboard/_last_announcements.blade.php (DROP-IN REPLACEMENT) --}}
{{--
  Replace your existing partial with this simplified, responsive list that
  composes <x-announcement-card />. It keeps the same $announcements data shape
  exposed by CurrentCoursesAnnouncementsVM::toArray().

  Drop this file in: resources/views/dashboard/_last_announcements.blade.php
--}}

@php
    use Illuminate\Support\Str;
@endphp

<div id="last-announcements" class="flex flex-col gap-3">
    @if (empty($announcements))
        <p class="text-sm text-slate-400">Sem anúncios recentes.</p>
    @else
        <div class="grid grid-cols-1 gap-3">
            @foreach ($announcements as $a)
                @php
                    $preview = Str::of(strip_tags($a['description'] ?? ''))
                        ->squish()
                        ->limit(240, '…');
                @endphp
                <x-announcement-card :title="$a['title']" :href="$a['link'] ?? null" :course="$a['courseName'] ?? null" :category="$a['category'] ?? null"
                    :author="$a['author'] ?? null" :published="$a['publishedAt'] ?? null" :preview="$preview" :html="$a['description'] ?? null" />
            @endforeach
        </div>
    @endif
</div>


{{-- Optional: Example wiring in your dashboard view (for reference only)

  <section class="xl:col-span-2">
    <div class="flex items-center justify-between mb-2">
      <h2 class="font-semibold">Últimos Anúncios</h2>
    </div>
    @include('dashboard._last_announcements')
  </section>

--}}
