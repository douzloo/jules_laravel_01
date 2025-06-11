<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PDF Viewer Landing Page</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="min-h-screen flex flex-col">
        {{-- Wrapper for PDF selection and viewer --}}
        <div class="w-full flex-grow flex flex-col p-4 md:p-6 lg:p-8">

            {{-- PDF Selection Area --}}
            <div class="mb-4 flex-shrink-0">
                @if(!$pdfDirectoryExists)
                    <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
                        PDF directory (`public/pdf`) not found. Please create it and add PDF files.
                    </div>
                @elseif(empty($pdfFiles))
                    <div class="p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-900 dark:text-yellow-300" role="alert">
                        No PDF files found in `public/pdf`. Using default sample.
                    </div>
                @else
                    <div>
                        <label for="pdf_select" class="block text-sm font-medium">Select PDF:</label>
                        <select id="pdf_select" name="pdf_select" class="mt-1 block w-full md:w-1/2 lg:w-1/3 pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white dark:bg-gray-700 dark:text-gray-200">
                            @foreach($pdfFiles as $pdf)
                                <option value="{{ $pdf }}" {{ $pdf === $defaultPdf ? 'selected' : '' }}>
                                    {{ $pdf }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            {{-- PDF Viewer Container --}}
            <div id="pdf-viewer-container" class="flex-grow bg-white dark:bg-gray-800 p-2 rounded-lg shadow-lg overflow-y-auto" data-default-pdf="{{ $defaultPdf ?? '' }}">
                <div class="initial_loading_indicator text-center p-10">
                    <p class="text-lg text-gray-500 dark:text-gray-400">Loading PDF...</p>
                </div>
            </div>
            {{-- Removed file upload and footer --}}
        </div>
    </div>
</body>
</html>
