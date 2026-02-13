{{--
    Arrow Indicator Component
    
    Props:
    - direction: 'left' | 'right' (default: 'left')
    - col: column index (int) for data attribute
    - page: page number (int) for data attribute
    
    Usage:
    <x-arrow-indicator direction="left" :col="0" :page="1" />
    
    Arrow size controlled via CSS variables:
    --arrow-width (default: 20mm)
    --arrow-height (default: 7mm)
--}}

@props([
    'direction' => 'left',
    'col' => 0,
    'page' => 1,
])

<div class="arrow-cell" onclick="toggleArrow(this)" data-col="{{ $col }}" data-page="{{ $page }}">
    <div class="arrow-container" data-direction="{{ $direction }}">
        @if($direction === 'left')
        {{-- Left Arrow --}}
        <svg class="arrow-left" viewBox="0 0 100 30">
            <polygon points="0,15 20,0 20,10 100,10 100,20 20,20 20,30" fill="black"/>
        </svg>
        @else
        {{-- Right Arrow --}}
        <svg class="arrow-right" viewBox="0 0 100 30">
            <polygon points="100,15 80,0 80,10 0,10 0,20 80,20 80,30" fill="black"/>
        </svg>
        @endif
    </div>
</div>
