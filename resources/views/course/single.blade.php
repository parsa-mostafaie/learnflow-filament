@php
  use App\Models\Course;
  use Illuminate\Support\Facades\Gate;

  // Fetch the course with the given slug, including trashed courses
  $course = Course::withTrashed()->where('slug', $id)->withCount('enrolls')->firstOrFail();

  Gate::authorize('view', $course);

  $strippedText = strip_tags(str($course->description)->markdown()->sanitizeHtml());

  // Truncate plain text only for preview
  $truncatedText = Str::limit($strippedText, 350, '...');
@endphp

<x-app-layout title="{{ $course->title }}">
  <x-slot name="meta">
    <meta name="description" content="{{ $truncatedText }}">
    <meta name="author" content="{{ $course->user->name }}">
    <meta property="og:description" content="{{ $truncatedText }}">
    <meta property="og:type" content="course">
    <meta property="og:image" content="{{ $course->image_url }}">
    <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "{{ $course->title }}",
        "description": "{{ $course->description }}",
        "image": "{{ $course->image_url }}",
        "provider": {
          "@type": "Organization",
          "name": "{{ __(config('app.name', 'LearnFlow')) }}",
          "url": "{{ url('/') }}"
        },
        "hasCourseInstance": {
          "@type": "CourseInstance",
          "name": "{{ $course->title }}",
          "courseMode": "online",
          "startDate": "{{ $course->created_at->toIso8601String() }}",
          "instructor": {
            "@type": "Person",
            "name": "{{ $course->user->name }}"
          },
          "location": {
            "@type": "VirtualLocation",
            "url": "{{ url()->current() }}"
          },
          "offers": {
            "@type": "Offer",
            "price": "0.00",
            "priceCurrency": "IRR",
            "availability": "https://schema.org/InStock",
            "url": "{{ url()->current() }}"
          }
        }
      }
      </script>
  </x-slot>

  {{-- Header slot --}}
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ $course->title }}
    </h2>
  </x-slot>

  {{-- Main content section --}}
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Course card container --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          {{-- Livewire component for the course card --}}
          {{-- <livewire:courses.card :$course /> --}}
          <livewire:courses.infolist :$course />
        </div>
      </div>
      {{-- Livewire component for course learning section --}}
      <livewire:courses.learning :$course />

      @can('seeEnrolls', $course)
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-2 overflow-visible">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <livewire:enrolled-users-table :$course lazy />
          </div>
        </div>
      @endcan
    </div>
  </div>
</x-app-layout>
