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
<body class="font-sans antialiased">
    <div class="bg-gray-50 text-black/50">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <h1 class="text-3xl font-semibold text-black">PDF Viewer</h1>
                    </div>
                </header>

                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-1 lg:gap-8">
                        <div class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20]">
                            <div class="pt-3 sm:pt-5">
                                <h2 class="text-xl font-semibold text-black">Landing Page Content</h2>
                                <p class="mt-4 text-sm/relaxed">
                                    Select a PDF from the dropdown to view it, or upload your own.
                                </p>

                                @if(!$pdfDirectoryExists)
                                    <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                        The PDF directory (`public/pdf`) does not exist. Please create it and add PDF files.
                                    </div>
                                @elseif(empty($pdfFiles))
                                    <div class="mt-4 p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg" role="alert">
                                        No PDF files found in the `public/pdf` directory. The viewer will load a default sample.
                                    </div>
                                @else
                                    <div class="mt-4">
                                        <label for="pdf_select" class="block text-sm font-medium text-gray-700">Select PDF:</label>
                                        <select id="pdf_select" name="pdf_select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            @foreach($pdfFiles as $pdf)
                                                <option value="{{ $pdf }}" {{ $pdf === $defaultPdf ? 'selected' : '' }}>
                                                    {{ $pdf }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div id="pdf-viewer-container" class="mt-6 p-2 rounded-lg shadow-lg overflow-y-auto h-[70vh]" data-default-pdf="{{ $defaultPdf ?? '' }}">
                                    {{-- Canvases will be appended here by PDF.js --}}
                                    {{-- Add a loading indicator or placeholder --}}
                                    <div class_initial_loading_indicator="text-center p-10">
                                        <p class="text-lg text-gray-500">Loading PDF...</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label for="pdf_upload" class="block text-sm font-medium text-gray-700">Upload PDF (optional):</label>
                                    <input type="file" id="pdf_upload" name="pdf_upload" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </footer>
            </div>
        </div>
    </div>
</body>
</html>
