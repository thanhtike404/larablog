@props(['block'])

@switch($block['type'])
@case('paragraph')
<p class="mb-6 text-gray-800 leading-relaxed">
    {!! nl2br(e($block['data']['text'])) !!}
</p>
@break

@case('heading')
@php
$level = $block['data']['level'] ?? 2;
$classes = [
1 => 'text-4xl font-bold mb-6 mt-8 text-gray-900',
2 => 'text-3xl font-bold mb-5 mt-7 text-gray-900',
3 => 'text-2xl font-bold mb-4 mt-6 text-gray-900',
4 => 'text-xl font-bold mb-3 mt-5 text-gray-900',
5 => 'text-lg font-bold mb-3 mt-4 text-gray-900',
6 => 'text-base font-bold mb-2 mt-3 text-gray-900'
];
@endphp
<h{{ $level }} class="{{ $classes[$level] ?? $classes[2] }}">
    {{ $block['data']['text'] }}
</h{{ $level }}>
@break

@case('image')
<figure class="my-8">
    <img src="{{ $block['data']['url'] }}"
        alt="{{ $block['data']['alt'] ?? '' }}"
        class="w-full rounded-lg shadow-md"
        @if(isset($block['data']['width']) && isset($block['data']['height']))
        width="{{ $block['data']['width'] }}"
        height="{{ $block['data']['height'] }}"
        @endif>
    @if(isset($block['data']['caption']))
    <figcaption class="text-center text-gray-600 text-sm mt-2 italic">
        {{ $block['data']['caption'] }}
    </figcaption>
    @endif
</figure>
@break

@case('image_gallery')
<div class="my-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($block['data']['images'] as $image)
        <figure>
            <img src="{{ $image['url'] }}"
                alt="{{ $image['alt'] ?? '' }}"
                class="w-full rounded-lg shadow-md">
            @if(isset($image['caption']))
            <figcaption class="text-center text-gray-600 text-sm mt-2 italic">
                {{ $image['caption'] }}
            </figcaption>
            @endif
        </figure>
        @endforeach
    </div>
</div>
@break

@case('quote')
<blockquote class="my-8 p-6 bg-gray-50 border-l-4 border-blue-500 rounded-r-lg">
    <p class="text-lg text-gray-800 italic mb-4">
        "{{ $block['data']['text'] }}"
    </p>
    @if(isset($block['data']['author']))
    <footer class="text-gray-600">
        <cite class="font-medium">
            — {{ $block['data']['author'] }}
            @if(isset($block['data']['cite']))
            , {{ $block['data']['cite'] }}
            @endif
        </cite>
    </footer>
    @endif
</blockquote>
@break

@case('code')
<div class="my-8">
    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code class="language-{{ $block['data']['language'] ?? 'text' }}">{{ $block['data']['code'] }}</code></pre>
</div>
@break

@case('list')
<div class="my-6">
    @if($block['data']['type'] === 'ordered')
    <ol class="list-decimal list-inside space-y-2 text-gray-800">
        @foreach($block['data']['items'] as $item)
        <li>{{ $item }}</li>
        @endforeach
    </ol>
    @else
    <ul class="list-disc list-inside space-y-2 text-gray-800">
        @foreach($block['data']['items'] as $item)
        <li>{{ $item }}</li>
        @endforeach
    </ul>
    @endif
</div>
@break

@default
<!-- Unknown block type -->
<div class="my-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
    <p class="text-yellow-800">Unknown content block type: {{ $block['type'] }}</p>
</div>
@endswitch