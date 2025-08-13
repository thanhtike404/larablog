<?php

namespace App\Livewire\Posts\Filter;

use Livewire\Component;

class PostFilters extends Component
{
    public function mount()
    {
        // Individual filter components now handle their own data loading
    }

    public function placeholder()
    {
        return <<<'HTML'
    <div class="lg:col-span-1 animate-pulse">
        <div class="space-y-6">
            <!-- Search Placeholder -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="h-10 bg-gray-200 rounded"></div>
            </div>

            <!-- Categories Placeholder -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-3">
                <div class="h-5 w-32 bg-gray-200 rounded"></div>
                <div class="space-y-2">
                    <div class="h-4 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded"></div>
                </div>
            </div>

            <!-- Tags Placeholder -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-3">
                <div class="h-5 w-20 bg-gray-200 rounded"></div>
                <div class="flex flex-wrap gap-2">
                    <div class="h-6 w-12 bg-gray-200 rounded-full"></div>
                    <div class="h-6 w-16 bg-gray-200 rounded-full"></div>
                    <div class="h-6 w-10 bg-gray-200 rounded-full"></div>
                </div>
            </div>

            <!-- Authors Placeholder -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-3">
                <div class="h-5 w-24 bg-gray-200 rounded"></div>
                <div class="space-y-2">
                    <div class="h-4 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded"></div>
                </div>
            </div>
        </div>
    </div>
    HTML;
    }

    public function render()
    {
        return view('livewire.posts.filter.post-filters');
    }
}
