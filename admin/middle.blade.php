@php
    /**
     * @var \Confetti\Helpers\ContentStore $contentStore
     * @var \Confetti\Helpers\ComponentStore $componentStore
     * @var string $currentContentId
     */
    $componentKey = \Confetti\Helpers\ComponentStandard::keyFromId($currentContentId);
    // Get parent content id
    // \w|~ remove word characters (with ulid)
    // /-/ remove target ids
    $parentContentId = preg_replace('#/(\w|~|/-/)+$#', '', $currentContentId);
    $hasParent = str_contains($currentContentId, '~');
    $components = $componentStore->whereParentKey($componentKey);
@endphp
<div class="container px-6 mx-auto grid">
    @if($parentContentId && $parentContentId !== '/section')
        <a
                class="flex items-center justify-between w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                href="/admin{{ $parentContentId }}"
        >
            < Back
        </a>
    @endif
    @foreach($components as $component)
        @php($suffix = str_replace($componentKey, '', $component->key))
        @include("structure.{$component->type}_component_admin", ['componentStore' => $componentStore, 'component' => $component, 'contentStore' => $contentStore, 'contentId' => $currentContentId . $suffix])
    @endforeach
    {{-- Ensure that we have the parent id for every item --}}
    <input type="hidden" name="{{ $currentContentId }}" value="__is_parent" x-bind="field" x-init="$dispatch('saveThisField')">
    <button
            class="flex items-center justify-between w-full px-4 py-2 mt-8 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
            parent-content-id="{{ $parentContentId }}"
            has-parent="{{ $hasParent }}"
            x-bind="submit"
            x-show="countFields() > 1"
    >
        Save
    </button>
</div>
@pushonce('script_middle')
    <script>
        function countFields() {
            return document.querySelectorAll("[x-bind='field']").length;
        }
    </script>
@endpushonce
