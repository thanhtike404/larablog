<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div x-data="blogEditor()" class="min-h-screen w-full">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .content-block:hover .remove-block {
            opacity: 1;
        }

        .content-block textarea {
            resize: vertical;
        }

        .content-block pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .prose pre {
            background-color: #1a202c !important;
            color: #e2e8f0 !important;
        }

        .prose code {
            background-color: transparent !important;
            color: inherit !important;
            padding: 0 !important;
        }

        .notification {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-900">Create New Blog Post</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="togglePreview()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </button>
                    <div class="relative">
                        <button @click="showDraftMenu = !showDraftMenu" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-save mr-2"></i>Draft
                            <i class="fas fa-chevron-down ml-1"></i>
                        </button>
                        <div x-show="showDraftMenu" @click.outside="showDraftMenu = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                            x-transition>
                            <div class="py-1">
                                <button @click="saveDraft(); showDraftMenu = false" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-save mr-2"></i>Save Draft
                                </button>
                                <button @click="clearDraft(); showDraftMenu = false" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-trash mr-2"></i>Clear Draft
                                </button>
                            </div>
                        </div>
                    </div>
                    <button @click="publishPost()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i>Publish
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Main Editor Column (8 columns) -->
            <div class="lg:col-span-8">
                <!-- Post Title -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Post Title</label>
                            <input type="text" id="title" x-model="title" @input="updateSlug()" placeholder="Enter your blog post title..."
                                class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    /blog/
                                </span>
                                <input type="text" id="slug" x-model="slug" placeholder="auto-generated-slug"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Editor -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <h3 class="text-lg font-medium text-gray-900">Content</h3>
                            <span class="text-sm text-gray-500" x-text="`${getWordCount()} words`"></span>
                        </div>
                        <div class="relative">
                            <button @click="showBlockMenu = !showBlockMenu" class="px-3 py-1 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100">
                                <i class="fas fa-plus mr-1"></i>Add Block
                            </button>
                            <div x-show="showBlockMenu" @click.outside="showBlockMenu = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                                x-transition>
                                <div class="py-1">
                                    <button @click="addContentBlock('paragraph')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-paragraph mr-2"></i>Paragraph
                                    </button>
                                    <button @click="addContentBlock('heading')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-heading mr-2"></i>Heading
                                    </button>
                                    <button @click="addContentBlock('code')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-code mr-2"></i>Code Block
                                    </button>
                                    <button @click="addContentBlock('list')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-list mr-2"></i>List
                                    </button>
                                    <button @click="addContentBlock('quote')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-quote-left mr-2"></i>Quote
                                    </button>
                                    <button @click="addContentBlock('image')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-image mr-2"></i>Image
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Blocks Container -->
                    <div class="space-y-4">
                        <template x-for="(block, index) in contentBlocks" :key="block.id">
                            <div class="content-block group relative border border-gray-200 rounded-lg p-4 hover:border-gray-300 hover:shadow-sm transition-all">
                                <!-- Block Header with Controls -->
                                <div class="flex items-center justify-between mb-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide" x-text="block.type"></span>
                                        <div class="flex space-x-1">
                                            <button @click="moveBlock(index, 'up')" :disabled="index === 0"
                                                :class="index === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-400 hover:text-gray-600'"
                                                class="p-1 rounded" title="Move up">
                                                <i class="fas fa-chevron-up text-xs"></i>
                                            </button>
                                            <button @click="moveBlock(index, 'down')" :disabled="index === contentBlocks.length - 1"
                                                :class="index === contentBlocks.length - 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-400 hover:text-gray-600'"
                                                class="p-1 rounded" title="Move down">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button @click="removeBlock(index)"
                                        :class="contentBlocks.length === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-red-500 hover:text-red-700 hover:bg-red-50'"
                                        :disabled="contentBlocks.length === 1"
                                        class="p-2 rounded-full transition-colors" title="Remove block">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                                <div class="content-area">
                                    <div class="flex-1">
                                        <!-- Paragraph Block -->
                                        <div x-show="block.type === 'paragraph'">
                                            <textarea x-model="block.data.text" placeholder="Start writing your content..."
                                                class="w-full min-h-[100px] border-0 resize-none focus:ring-0 focus:outline-none text-gray-900 placeholder-gray-400"
                                                style="font-family: inherit;"></textarea>
                                        </div>

                                        <!-- Heading Block -->
                                        <div x-show="block.type === 'heading'" class="space-y-2">
                                            <select x-model="block.data.level" class="text-sm border border-gray-300 rounded px-2 py-1">
                                                <option value="1">H1</option>
                                                <option value="2">H2</option>
                                                <option value="3">H3</option>
                                                <option value="4">H4</option>
                                            </select>
                                            <input type="text" x-model="block.data.text" placeholder="Enter heading text..."
                                                class="w-full text-xl font-semibold border-0 focus:ring-0 focus:outline-none text-gray-900 placeholder-gray-400">
                                        </div>

                                        <!-- Code Block -->
                                        <div x-show="block.type === 'code'" class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <select x-model="block.data.language" class="text-sm border border-gray-300 rounded px-2 py-1">
                                                    <option value="php">PHP</option>
                                                    <option value="javascript">JavaScript</option>
                                                    <option value="html">HTML</option>
                                                    <option value="css">CSS</option>
                                                    <option value="python">Python</option>
                                                    <option value="json">JSON</option>
                                                    <option value="sql">SQL</option>
                                                    <option value="bash">Bash</option>
                                                </select>
                                                <span class="text-xs text-gray-500">Language</span>
                                            </div>
                                            <div class="relative">
                                                <textarea x-model="block.data.code" placeholder="Enter your code..."
                                                    class="w-full min-h-[120px] border border-gray-300 rounded p-3 font-mono text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                                    style="tab-size: 4; white-space: pre; overflow-wrap: normal; overflow-x: auto;"></textarea>
                                                <div class="absolute top-2 right-2">
                                                    <button @click="copyCodeToClipboard(block.data.code)" class="text-gray-400 hover:text-gray-600 text-xs" title="Copy code">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- List Block -->
                                        <div x-show="block.type === 'list'" class="space-y-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex space-x-2">
                                                    <button @click="block.data.style = 'unordered'"
                                                        :class="block.data.style === 'unordered' ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50'"
                                                        class="px-2 py-1 text-sm border border-gray-300 rounded">
                                                        <i class="fas fa-list-ul"></i> Unordered
                                                    </button>
                                                    <button @click="block.data.style = 'ordered'"
                                                        :class="block.data.style === 'ordered' ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50'"
                                                        class="px-2 py-1 text-sm border border-gray-300 rounded">
                                                        <i class="fas fa-list-ol"></i> Ordered
                                                    </button>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Press Enter to add new item
                                                </div>
                                            </div>
                                            <div class="list-items space-y-2">
                                                <template x-for="(item, itemIndex) in block.data.items" :key="itemIndex">
                                                    <div class="group flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                                        <div class="flex-shrink-0 w-6 text-center">
                                                            <span class="text-sm text-gray-400" x-show="block.data.style === 'ordered'" x-text="itemIndex + 1 + '.'"></span>
                                                            <span class="text-sm text-gray-400" x-show="block.data.style === 'unordered'">•</span>
                                                        </div>
                                                        <input type="text" x-model="block.data.items[itemIndex]" placeholder="List item..."
                                                            @keydown="handleListItemKeydown($event, block, itemIndex)"
                                                            @keydown.backspace="handleListItemBackspace($event, block, itemIndex)"
                                                            class="flex-1 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        <div class="flex items-center space-x-1 opacity-30 group-hover:opacity-100 transition-opacity">
                                                            <button @click="addListItem(block, $event)" class="p-1.5 text-green-600 hover:bg-green-100 rounded-full transition-colors" title="Add item">
                                                                <i class="fas fa-plus text-xs"></i>
                                                            </button>
                                                            <button @click="removeListItem(block, itemIndex)"
                                                                :class="block.data.items.length === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-red-600 hover:bg-red-100'"
                                                                :disabled="block.data.items.length === 1"
                                                                class="p-1.5 rounded-full transition-colors" title="Remove item">
                                                                <i class="fas fa-trash text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                                <!-- Add new item button at the end -->
                                                <div class="flex items-center space-x-2 p-2 rounded-lg border-2 border-dashed border-gray-200 hover:border-gray-300 transition-colors">
                                                    <div class="flex-shrink-0 w-6 text-center">
                                                        <span class="text-sm text-gray-300" x-show="block.data.style === 'ordered'" x-text="block.data.items.length + 1 + '.'"></span>
                                                        <span class="text-sm text-gray-300" x-show="block.data.style === 'unordered'">•</span>
                                                    </div>
                                                    <button @click="addListItem(block, $event)" class="flex-1 text-left px-3 py-2 text-gray-400 hover:text-gray-600 transition-colors">
                                                        <i class="fas fa-plus mr-2"></i>Add new item
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quote Block -->
                                        <div x-show="block.type === 'quote'" class="space-y-2">
                                            <textarea x-model="block.data.text" placeholder="Enter quote text..."
                                                class="w-full min-h-[80px] border border-gray-300 rounded p-3 italic focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                            <input type="text" x-model="block.data.caption" placeholder="Citation/Author (optional)"
                                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <!-- Image Block -->
                                        <div x-show="block.type === 'image'" class="space-y-3">
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center" x-show="!block.data.url">
                                                <i class="fas fa-image text-gray-400 text-2xl mb-2"></i>
                                                <div class="text-sm text-gray-600">
                                                    <label class="cursor-pointer text-blue-600 hover:text-blue-500">
                                                        <span>Upload image</span>
                                                        <input type="file" class="hidden" accept="image/*" @change="handleContentImageUpload($event, block)">
                                                    </label>
                                                </div>
                                            </div>
                                            <div x-show="block.data.url" class="relative">
                                                <img :src="block.data.url" class="max-w-full h-48 object-cover rounded border mx-auto">
                                                <button @click="clearContentImage(block)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <input type="text" x-model="block.data.caption" placeholder="Image caption (optional)"
                                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <input type="text" x-model="block.data.alt" placeholder="Alt text"
                                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Sidebar (4 columns) -->
            <div class="lg:col-span-4">
                <div class="space-y-6">

                    <!-- Publishing Options -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="isPublished" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Publish immediately</span>
                                </label>
                            </div>
                            <div>
                                <label for="publishDate" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                                <input type="datetime-local" x-model="publishDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Featured Images -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Featured Images</h3>
                        <div class="space-y-4">
                            <!-- Main Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center" x-show="!mainImagePreview">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                                        <div class="text-sm text-gray-600">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Upload main image</span>
                                                <input type="file" class="sr-only" accept="image/*" @change="handleMainImageUpload($event)">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                    <div x-show="mainImagePreview" class="relative">
                                        <img :src="mainImagePreview" class="max-w-full h-32 object-cover rounded border">
                                        <button @click="clearMainImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Secondary Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Image</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center" x-show="!secondaryImagePreview">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                                        <div class="text-sm text-gray-600">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Upload image</span>
                                                <input type="file" class="sr-only" accept="image/*" @change="handleSecondaryImageUpload($event)">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 1MB</p>
                                    </div>
                                    <div x-show="secondaryImagePreview" class="relative">
                                        <img :src="secondaryImagePreview" class="max-w-full h-32 object-cover rounded border">
                                        <button @click="clearSecondaryImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Category</h3>
                        <select x-model="categoryId" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tags</h3>
                        <div class="space-y-3">
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in selectedTags" :key="tag.id">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <span x-text="tag.name"></span>
                                        <button @click="removeTag(tag.id)" class="ml-1 text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </span>
                                </template>
                            </div>
                            <div class="relative">
                                <input type="text" x-model="tagSearch" @click="showTagDropdown = true" @input="filterTags()"
                                    placeholder="Search tags..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <div x-show="showTagDropdown" @click.away="showTagDropdown = false"
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-40 overflow-auto">
                                    <div class="py-1">

                                        <template x-for="tag in {{$tags}}" :key="tag.id">
                                            <label class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                                                <input type="checkbox" :value="tag.id" :checked="isTagSelected(tag.id)"
                                                    @change="toggleTag(tag)" class="rounded border-gray-300 text-blue-600 mr-2">
                                                <span class="text-sm" x-text="tag.name"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreview" class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="showPreview = false"></div>
            <div class="relative bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Preview</h3>
                    <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="prose prose-lg max-w-none" x-html="previewContent">
                    </div>
                </div>
                <script>
                    // Global function for copy functionality in preview
                    function copyToClipboard(text, button) {
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(text).then(() => {
                                const originalText = button.innerHTML;
                                button.innerHTML = '<i class="fas fa-check"></i> Copied!';
                                setTimeout(() => {
                                    button.innerHTML = originalText;
                                }, 2000);
                            });
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<script>
    function blogEditor() {
        return {
            // Form data
            title: '',
            slug: '',
            categoryId: '',
            isPublished: false,
            publishDate: new Date().toISOString().slice(0, 16),

            // Content blocks
            contentBlocks: [{
                id: 'block_1',
                type: 'paragraph',
                data: {
                    text: ''
                },
                order: 1
            }],
            blockCounter: 1,

            // UI state
            showBlockMenu: false,
            showPreview: false,
            showTagDropdown: false,
            showDraftMenu: false,

            // Tags
            selectedTags: [],
            tagSearch: '',
            availableTags: [{
                    id: '1',
                    name: 'JavaScript'
                },
                {
                    id: '2',
                    name: 'PHP'
                },
                {
                    id: '3',
                    name: 'Laravel'
                },
                {
                    id: '4',
                    name: 'Web Development'
                },
                {
                    id: '5',
                    name: 'UI/UX'
                }
            ],
            filteredTags: [],

            // Images
            mainImagePreview: null,
            secondaryImagePreview: null,

            init() {
                // Initialize tags
                this.filteredTags = [...this.availableTags];
                this.showTagDropdown = false;
                this.selectedTags = [];

                // Auto-save every 30 seconds
                setInterval(() => {
                    if (this.title.trim()) {
                        this.autoSave();
                    }
                }, 30000);

                // Load from localStorage if available
                this.loadFromLocalStorage();

                // Add keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    // Ctrl/Cmd + S to save draft
                    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                        e.preventDefault();
                        this.saveDraft();
                    }

                    // Ctrl/Cmd + Enter to publish
                    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                        e.preventDefault();
                        this.publishPost();
                    }

                    // Ctrl/Cmd + P to preview
                    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                        e.preventDefault();
                        this.togglePreview();
                    }
                });
            },

            // Slug generation
            updateSlug() {
                this.slug = this.generateSlug(this.title);
            },

            generateSlug(text) {
                return text
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            },

            // Content blocks management
            addContentBlock(type) {
                this.blockCounter++;
                const block = {
                    id: `block_${this.blockCounter}`,
                    type: type,
                    data: this.getDefaultBlockData(type),
                    order: this.contentBlocks.length + 1
                };
                this.contentBlocks.push(block);
                this.showBlockMenu = false;
            },

            getDefaultBlockData(type) {
                const defaults = {
                    paragraph: {
                        text: ''
                    },
                    heading: {
                        text: '',
                        level: 2
                    },
                    code: {
                        code: '',
                        language: 'php'
                    },
                    list: {
                        items: [''],
                        style: 'unordered'
                    },
                    quote: {
                        text: '',
                        caption: ''
                    },
                    image: {
                        url: '',
                        caption: '',
                        alt: ''
                    }
                };
                return defaults[type] || {
                    text: ''
                };
            },

            removeBlock(index) {
                if (this.contentBlocks.length > 1) {
                    const blockType = this.contentBlocks[index].type;
                    this.contentBlocks.splice(index, 1);
                    this.reorderBlocks();
                    this.showNotification(`${blockType.charAt(0).toUpperCase() + blockType.slice(1)} block removed`, 'success');
                } else {
                    this.showNotification('Cannot remove the last content block', 'error');
                }
            },

            moveBlock(index, direction) {
                if (direction === 'up' && index > 0) {
                    [this.contentBlocks[index], this.contentBlocks[index - 1]] = [this.contentBlocks[index - 1], this.contentBlocks[index]];
                    this.reorderBlocks();
                    this.showNotification('Block moved up', 'success');
                } else if (direction === 'down' && index < this.contentBlocks.length - 1) {
                    [this.contentBlocks[index], this.contentBlocks[index + 1]] = [this.contentBlocks[index + 1], this.contentBlocks[index]];
                    this.reorderBlocks();
                    this.showNotification('Block moved down', 'success');
                }
            },

            reorderBlocks() {
                this.contentBlocks.forEach((block, index) => {
                    block.order = index + 1;
                });
            },

            // List items management
            addListItem(block, event) {
                block.data.items.push('');

                // Focus on the new input after DOM update
                this.$nextTick(() => {
                    const listContainer = event ? event.target.closest('.list-items') : document.querySelector('.list-items');
                    if (listContainer) {
                        const inputs = listContainer.querySelectorAll('input[type="text"]');
                        const lastInput = inputs[inputs.length - 1];
                        if (lastInput) {
                            lastInput.focus();
                        }
                    }
                });
            },

            removeListItem(block, itemIndex) {
                if (block.data.items.length > 1) {
                    block.data.items.splice(itemIndex, 1);

                    // Focus on the previous item if it exists, otherwise focus on the next item
                    this.$nextTick(() => {
                        const listContainer = document.querySelector('.list-items');
                        if (listContainer) {
                            const inputs = listContainer.querySelectorAll('input[type="text"]');
                            const focusIndex = itemIndex > 0 ? itemIndex - 1 : 0;
                            if (inputs[focusIndex]) {
                                inputs[focusIndex].focus();
                                // Move cursor to end of input
                                inputs[focusIndex].setSelectionRange(inputs[focusIndex].value.length, inputs[focusIndex].value.length);
                            }
                        }
                    });
                } else {
                    // Show feedback that you can't remove the last item
                    this.showNotification('Cannot remove the last list item', 'error');
                }
            },

            // Enhanced list item keyboard handling
            handleListItemKeydown(event, block, itemIndex) {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    // Add new list item after current one
                    block.data.items.splice(itemIndex + 1, 0, '');

                    // Focus on the new input after DOM update
                    this.$nextTick(() => {
                        const inputs = event.target.closest('.list-items').querySelectorAll('input[type="text"]');
                        if (inputs[itemIndex + 1]) {
                            inputs[itemIndex + 1].focus();
                        }
                    });
                } else if (event.key === 'ArrowUp' && itemIndex > 0) {
                    event.preventDefault();
                    const inputs = event.target.closest('.list-items').querySelectorAll('input[type="text"]');
                    inputs[itemIndex - 1].focus();
                } else if (event.key === 'ArrowDown' && itemIndex < block.data.items.length - 1) {
                    event.preventDefault();
                    const inputs = event.target.closest('.list-items').querySelectorAll('input[type="text"]');
                    inputs[itemIndex + 1].focus();
                }
            },

            handleListItemBackspace(event, block, itemIndex) {
                // If the current item is empty and there are multiple items, remove it
                if (event.target.value === '' && block.data.items.length > 1) {
                    event.preventDefault();

                    // Remove current item
                    block.data.items.splice(itemIndex, 1);

                    // Focus on previous item if it exists, otherwise focus on the next item
                    this.$nextTick(() => {
                        const inputs = event.target.closest('.list-items').querySelectorAll('input[type="text"]');
                        const focusIndex = itemIndex > 0 ? itemIndex - 1 : 0;
                        if (inputs[focusIndex]) {
                            inputs[focusIndex].focus();
                            // Move cursor to end of input
                            inputs[focusIndex].setSelectionRange(inputs[focusIndex].value.length, inputs[focusIndex].value.length);
                        }
                    });
                }
            },

            // Tag management
            toggleTag(tag) {
                if (this.isTagSelected(tag.id)) {
                    this.removeTag(tag.id);
                } else {
                    this.selectedTags.push(tag);
                    this.tagSearch = '';
                    this.filterTags();
                }
            },

            isTagSelected(tagId) {
                return this.selectedTags.some(tag => tag.id === tagId);
            },

            removeTag(tagId) {
                this.selectedTags = this.selectedTags.filter(tag => tag.id !== tagId);
            },

            filterTags() {
                const searchTerm = this.tagSearch.trim().toLowerCase();
                if (searchTerm === '') {
                    this.filteredTags = this.availableTags.filter(tag =>
                        !this.selectedTags.some(selected => selected.id === tag.id)
                    );
                } else {
                    this.filteredTags = this.availableTags.filter(tag =>
                        tag.name.toLowerCase().includes(searchTerm) &&
                        !this.selectedTags.some(selected => selected.id === tag.id)
                    );
                }
            },

            // Image handling
            handleMainImageUpload(event) {
                this.handleImageUpload(event, (url) => {
                    this.mainImagePreview = url;
                });
            },

            handleSecondaryImageUpload(event) {
                this.handleImageUpload(event, (url) => {
                    this.secondaryImagePreview = url;
                });
            },

            handleContentImageUpload(event, block) {
                this.handleImageUpload(event, (url) => {
                    block.data.url = url;
                });
            },

            handleImageUpload(event, callback) {
                const file = event.target.files[0];
                if (file) {
                    // Validate file size (max 2MB for main image, 1MB for others)
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        this.showNotification('Image size should be less than 2MB', 'error');
                        return;
                    }

                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        this.showNotification('Please select a valid image file', 'error');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => callback(e.target.result);
                    reader.readAsDataURL(file);
                }
            },

            clearMainImage() {
                this.mainImagePreview = null;
            },

            clearSecondaryImage() {
                this.secondaryImagePreview = null;
            },

            clearContentImage(block) {
                block.data.url = '';
            },

            // Code utilities
            copyCodeToClipboard(code) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(code).then(() => {
                        this.showNotification('Code copied to clipboard!', 'success');
                    }).catch(() => {
                        this.showNotification('Failed to copy code', 'error');
                    });
                } else {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = code;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        this.showNotification('Code copied to clipboard!', 'success');
                    } catch (err) {
                        this.showNotification('Failed to copy code', 'error');
                    }
                    document.body.removeChild(textArea);
                }
            },

            // Preview
            togglePreview() {
                this.generatePreview();
                this.showPreview = true;
            },

            generatePreview() {
                let html = `<h1 class="text-3xl font-bold mb-6">${this.title || 'Untitled Post'}</h1>`;

                // Add publish date
                if (this.publishDate) {
                    const date = new Date(this.publishDate);
                    html += `<p class="text-gray-600 mb-6">${date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })}</p>`;
                }

                // Add selected tags
                if (this.selectedTags.length > 0) {
                    html += `<div class="mb-6">`;
                    this.selectedTags.forEach(tag => {
                        html += `<span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">${tag.name}</span>`;
                    });
                    html += `</div>`;
                }

                // Generate content from blocks
                this.contentBlocks.forEach(block => {
                    switch (block.type) {
                        case 'paragraph':
                            if (block.data.text && block.data.text.trim()) {
                                html += `<p class="mb-4">${block.data.text}</p>`;
                            }
                            break;
                        case 'heading':
                            if (block.data.text && block.data.text.trim()) {
                                const sizes = {
                                    1: 'text-3xl',
                                    2: 'text-2xl',
                                    3: 'text-xl',
                                    4: 'text-lg'
                                };
                                html += `<h${block.data.level} class="${sizes[block.data.level]} font-semibold mb-4 mt-6">${block.data.text}</h${block.data.level}>`;
                            }
                            break;
                        case 'code':
                            if (block.data.code && block.data.code.trim()) {
                                html += `<div class="mb-4">
                                    <div class="bg-gray-800 text-gray-200 px-4 py-2 rounded-t-lg text-sm font-medium flex items-center justify-between">
                                        <span>${block.data.language.toUpperCase()}</span>
                                        <button onclick="copyToClipboard('${this.escapeHtml(block.data.code).replace(/'/g, "\\'")}', this)" class="text-gray-400 hover:text-white text-xs">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                    <pre class="bg-gray-900 text-gray-100 rounded-b-lg p-4 overflow-x-auto"><code class="language-${block.data.language}">${this.escapeHtml(block.data.code)}</code></pre>
                                </div>`;
                            }
                            break;
                        case 'list':
                            const listItems = block.data.items.filter(item => item && item.trim());
                            if (listItems.length > 0) {
                                const isOrdered = block.data.style === 'ordered';
                                const tag = isOrdered ? 'ol' : 'ul';
                                const className = isOrdered ? 'list-decimal list-inside mb-4' : 'list-disc list-inside mb-4';
                                html += `<${tag} class="${className}">`;
                                listItems.forEach(item => {
                                    html += `<li class="mb-1">${item}</li>`;
                                });
                                html += `</${tag}>`;
                            }
                            break;
                        case 'quote':
                            if (block.data.text && block.data.text.trim()) {
                                html += `<blockquote class="border-l-4 border-gray-300 pl-4 italic text-gray-700 mb-4">${block.data.text}`;
                                if (block.data.caption && block.data.caption.trim()) {
                                    html += `<cite class="block text-sm text-gray-500 mt-2">— ${block.data.caption}</cite>`;
                                }
                                html += `</blockquote>`;
                            }
                            break;
                        case 'image':
                            if (block.data.url) {
                                html += `<div class="mb-6 text-center">
                                    <img src="${block.data.url}" alt="${block.data.alt || ''}" class="max-w-full h-auto rounded-lg shadow-md mx-auto">
                                    ${block.data.caption ? `<p class="text-sm text-gray-600 italic mt-2">${block.data.caption}</p>` : ''}
                                </div>`;
                            } else {
                                html += `<div class="mb-4 text-center">
                                    <div class="bg-gray-200 rounded-lg p-8 mb-2">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                        <p class="text-gray-500 text-sm mt-2">Image placeholder</p>
                                    </div>
                                    ${block.data.caption ? `<p class="text-sm text-gray-600 italic">${block.data.caption}</p>` : ''}
                                </div>`;
                            }
                            break;
                    }
                });

                this.previewContent = html;
            },

            escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            },

            // Save operations
            saveDraft() {
                this.saveBlogPost(false);
            },

            publishPost() {

                this.saveBlogPost(true);
            },

            saveBlogPost(publish) {

                // Enhanced validation
                if (!this.title.trim()) {
                    this.showNotification('Please enter a title for your blog post.', 'error');
                    return;
                }

                if (!this.categoryId) {
                    this.showNotification('Please select a category.', 'error');
                    return;
                }

                // Check if there's any content
                const hasContent = this.contentBlocks.some(block => {
                    switch (block.type) {
                        case 'paragraph':
                        case 'heading':
                            return block.data.text && block.data.text.trim();
                        case 'code':
                            return block.data.code && block.data.code.trim();
                        case 'list':
                            return block.data.items && block.data.items.some(item => item && item.trim());
                        case 'quote':
                            return block.data.text && block.data.text.trim();
                        case 'image':
                            return block.data.url;
                        default:
                            return false;
                    }
                });

                if (!hasContent) {
                    this.showNotification('Please add some content to your blog post.', 'error');
                    return;
                }

                // Additional validation for publishing
                if (publish) {
                    const wordCount = this.getWordCount();
                    if (wordCount < 50) {
                        this.showNotification('Blog post should have at least 50 words for publishing.', 'error');
                        return;
                    }
                }

                const formData = {
                    title: this.title,
                    slug: this.slug,
                    category_id: this.categoryId,
                    selected_tags: this.selectedTags.map(tag => tag.id),
                    is_published: publish,
                    published_at: publish ? this.publishDate : null,
                    content_blocks: this.contentBlocks,
                    word_count: this.getWordCount()
                };

                try {
                    // this.$wire.logBlog();

                    this.$wire.saveBlogPost(formData);

                } catch (error) {
                    this.showNotification('An error occurred while saving the blog post.', 'error');
                    console.error('Save blog post error:', error);
                    return;
                }
                console.log('Saving blog post:', formData);

                // Clear auto-save draft if successfully saved
                if (publish) {
                    localStorage.removeItem('blog_post_draft');
                }

                const message = publish ? 'Blog post published successfully!' : 'Blog post saved as draft!';
                this.showNotification(message, 'success');
            },

            showNotification(message, type = 'success') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;

                const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
                notification.innerHTML = `
                    <i class="${icon}"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                document.body.appendChild(notification);

                // Remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.style.animation = 'slideOutRight 0.3s ease-in forwards';
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.remove();
                            }
                        }, 300);
                    }
                }, 5000);
            },

            // Auto-save functionality
            autoSave() {
                const data = {
                    title: this.title,
                    slug: this.slug,
                    categoryId: this.categoryId,
                    contentBlocks: this.contentBlocks,
                    selectedTags: this.selectedTags,
                    publishDate: this.publishDate,
                    isPublished: this.isPublished,
                    timestamp: Date.now()
                };

                localStorage.setItem('blog_post_draft', JSON.stringify(data));

                // Show subtle auto-save indicator
                const indicator = document.createElement('div');
                indicator.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-3 py-1 rounded text-sm z-50';
                indicator.textContent = 'Auto-saved';
                document.body.appendChild(indicator);

                setTimeout(() => {
                    if (indicator.parentNode) {
                        indicator.remove();
                    }
                }, 2000);
            },

            loadFromLocalStorage() {
                const saved = localStorage.getItem('blog_post_draft');
                if (saved) {
                    try {
                        const data = JSON.parse(saved);
                        // Only load if it's recent (within 24 hours)
                        if (Date.now() - data.timestamp < 24 * 60 * 60 * 1000) {
                            this.title = data.title || '';
                            this.slug = data.slug || '';
                            this.categoryId = data.categoryId || '';
                            this.contentBlocks = data.contentBlocks || this.contentBlocks;
                            this.selectedTags = data.selectedTags || [];
                            this.publishDate = data.publishDate || this.publishDate;
                            this.isPublished = data.isPublished || false;

                            if (this.title) {
                                this.showNotification('Draft restored from auto-save', 'success');
                            }
                        }
                    } catch (e) {
                        console.error('Failed to load draft from localStorage:', e);
                    }
                }
            },

            clearDraft() {
                localStorage.removeItem('blog_post_draft');
                this.showNotification('Draft cleared', 'success');
            },

            // Word count functionality
            getWordCount() {
                let totalWords = 0;

                this.contentBlocks.forEach(block => {
                    switch (block.type) {
                        case 'paragraph':
                        case 'heading':
                            if (block.data.text) {
                                totalWords += this.countWords(block.data.text);
                            }
                            break;
                        case 'list':
                            if (block.data.items) {
                                block.data.items.forEach(item => {
                                    if (item) {
                                        totalWords += this.countWords(item);
                                    }
                                });
                            }
                            break;
                        case 'quote':
                            if (block.data.text) {
                                totalWords += this.countWords(block.data.text);
                            }
                            if (block.data.caption) {
                                totalWords += this.countWords(block.data.caption);
                            }
                            break;
                    }
                });

                return totalWords;
            },

            countWords(text) {
                return text.trim().split(/\s+/).filter(word => word.length > 0).length;
            },

            // Computed property for preview content
            get previewContent() {
                return this._previewContent || '';
            },

            set previewContent(value) {
                this._previewContent = value;
            }
        }
    }
</script>