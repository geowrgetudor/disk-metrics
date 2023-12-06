<x-pulse::card :cols="$cols" :rows="$rows" :class="$class">
    <x-pulse::card-header name="{{ __('Disk Metrics') }}">
        <x-slot:icon>
            <x-dynamic-component :component="'pulse::icons.sparkles'" />
        </x-slot:icon>
    </x-pulse::card-header>

    <x-pulse::scroll :expand="$expand" wire:poll.5s="">
        @if (empty($data))
            <x-pulse::no-results />
        @else
            <x-pulse::table>
                <colgroup>
                    <col width="100%" />
                    <col width="0%" />
                    <col width="0%" />
                </colgroup>
                <x-pulse::thead>
                    <tr>
                        <x-pulse::th>{{ __('Disk') }}</x-pulse::th>
                        <x-pulse::th class="text-right">{{ __('Directories') }}</x-pulse::th>
                        <x-pulse::th class="text-right">{{ __('Files') }}</x-pulse::th>
                        <x-pulse::th class="text-right">{{ __('Size') }}</x-pulse::th>
                    </tr>
                </x-pulse::thead>
                <tbody>
                    @foreach ($data as $diskName => $details)
                        <tr class="h-2 first:h-0"></tr>
                        <tr wire:key="{{ $diskName }}">
                            <x-pulse::td class="max-w-[1px]">
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate"
                                    title="{{ $diskName }}">
                                    {{ ucfirst($diskName) }}
                                </code>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate" title="">
                                    @if ($details['disk_data']['root'])
                                        {{ $details['disk_data']['root'] }}
                                    @endif
                                    @if ($details['disk_data']['bucket'])
                                        {{ $details['disk_data']['bucket'] }}
                                    @endif
                                </p>
                            </x-pulse::td>
                            <x-pulse::td numeric class="text-gray-700 dark:text-gray-300 font-bold">
                                @if (isset($details['metrics']['directory_count']))
                                    {{ $details['metrics']['directory_count']->value }}
                                @endif
                            </x-pulse::td>
                            <x-pulse::td numeric class="text-gray-700 dark:text-gray-300 font-bold">
                                @if (isset($details['metrics']['file_count']))
                                    {{ $details['metrics']['file_count']->value }}
                                @endif
                            </x-pulse::td>
                            <x-pulse::td numeric class="text-gray-700 dark:text-gray-300 font-bold">
                                @if (isset($details['metrics']['total_size']))
                                    {{ $details['metrics']['total_size']->value }}
                                @endif
                            </x-pulse::td>
                        </tr>
                    @endforeach
                </tbody>
            </x-pulse::table>
        @endif
    </x-pulse::scroll>
</x-pulse::card>
