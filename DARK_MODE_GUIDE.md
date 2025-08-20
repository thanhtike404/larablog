# Dark Mode Implementation Guide

## Overview
Your Laravel Livewire project now has a complete dark mode implementation using Tailwind CSS and Alpine.js.

## Features
- ✅ Automatic system preference detection
- ✅ Persistent user preference storage
- ✅ Smooth transitions between modes
- ✅ No flash of unstyled content (FOUC)
- ✅ Accessible toggle button
- ✅ Consistent styling across all components

## How It Works

### 1. Tailwind Configuration
- `tailwind.config.js` has `darkMode: 'class'` enabled
- Dark mode is triggered by adding the `dark` class to the `<html>` element

### 2. Dark Mode Toggle Component
- Located at `resources/views/components/dark-mode-toggle.blade.php`
- Uses Alpine.js for state management
- Automatically detects system preference
- Persists user choice in localStorage

### 3. Layout Integration
- Main layout at `resources/views/components/layout.blade.php`
- Includes inline script to prevent FOUC
- Fixed-position toggle button in top-right corner

### 4. Styling Patterns
All components use Tailwind's dark mode classes:
```html
<!-- Background colors -->
class="bg-white dark:bg-gray-800"

<!-- Text colors -->
class="text-gray-900 dark:text-gray-100"

<!-- Border colors -->
class="border-gray-300 dark:border-gray-600"

<!-- Form inputs -->
class="bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-300"
```

### 5. Custom CSS Utilities
Added utility classes in `resources/css/app.css`:
- `.form-input` - Styled form inputs with dark mode
- `.form-label` - Styled form labels with dark mode
- `.card` - Card component with dark mode
- `.btn-primary` - Primary button with dark mode

## Updated Components

### ✅ Layout (`resources/views/components/layout.blade.php`)
- Added dark mode toggle
- Improved footer styling
- FOUC prevention script

### ✅ Navbar (`resources/views/components/navbar.blade.php`)
- Dark background and text colors
- Hover states for dark mode
- Mobile menu dark mode support

### ✅ User Table (`resources/views/livewire/user-table.blade.php`)
- Table headers and rows with dark styling
- Form inputs with dark mode
- Consistent button styling

### ✅ Pages
- Home page (`resources/views/home.blade.php`)
- About page (`resources/views/about.blade.php`)
- Contact page (`resources/views/contact.blade.php`)
- Posts page (`resources/views/posts.blade.php`)

## Usage

### For Developers
When creating new components, follow these patterns:

```html
<!-- Container/Card -->
<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">

<!-- Headings -->
<h1 class="text-gray-900 dark:text-gray-100">

<!-- Body text -->
<p class="text-gray-700 dark:text-gray-300">

<!-- Muted text -->
<span class="text-gray-500 dark:text-gray-400">

<!-- Form inputs -->
<input class="bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-300">

<!-- Buttons -->
<button class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white">
```

### For Users
- Click the sun/moon icon in the top-right corner to toggle dark mode
- The preference is automatically saved and will persist across sessions
- The system will respect your OS dark mode preference by default

## Browser Support
- All modern browsers that support CSS custom properties
- Graceful degradation for older browsers (falls back to light mode)

## Customization
To customize the dark mode colors, edit the Tailwind classes in your components or extend the theme in `tailwind.config.js`.

## Troubleshooting
1. **Dark mode not working**: Ensure Alpine.js is loaded and the toggle component is included
2. **Flash of light content**: Check that the inline script in the layout head is present
3. **Styles not applying**: Run `npm run build` to rebuild your CSS