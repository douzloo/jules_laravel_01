import './bootstrap';
import * as pdfjsLib from 'pdfjs-dist/build/pdf';
import PdfjsWorker from 'pdfjs-dist/build/pdf.worker.min.mjs?url'; // Vite specific import for worker

// If you're not using Alpine, you can remove these lines:
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();


pdfjsLib.GlobalWorkerOptions.workerSrc = PdfjsWorker;

const pdfViewerContainer = document.getElementById('pdf-viewer-container');
const pdfSelect = document.getElementById('pdf_select');
const pdfUpload = document.getElementById('pdf_upload');
let currentLoadingTask = null;

async function renderPdf(pdfUrl) {
    if (!pdfViewerContainer) {
        console.error('PDF viewer container not found.');
        return;
    }
    pdfViewerContainer.innerHTML = '<div class="text-center p-10 text-lg text-gray-500 dark:text-gray-400">Loading PDF...</div>';
    document.body.classList.add('loading-pdf');

    if (currentLoadingTask && typeof currentLoadingTask.destroy === 'function') {
        try {
            await currentLoadingTask.destroy();
            console.log('Previous PDF loading task cancelled.');
        } catch (e) {
            console.error('Error destroying previous loading task:', e);
        }
    }

    currentLoadingTask = pdfjsLib.getDocument(pdfUrl);

    try {
        const pdf = await currentLoadingTask.promise;
        console.log('PDF loaded:', pdfUrl);
        pdfViewerContainer.innerHTML = ''; // Clear loading message

        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            const page = await pdf.getPage(pageNum);
            // console.log(`Page ${pageNum} loaded`);

            const viewport = page.getViewport({ scale: 1.5 });
            const canvas = document.createElement('canvas');
            canvas.className = 'pdf-page-canvas block mx-auto my-2 border border-gray-400 dark:border-zinc-600 shadow-lg';
            const ctx = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            pdfViewerContainer.appendChild(canvas);

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport,
            };
            await page.render(renderContext).promise;
            // console.log(`Page ${pageNum} rendered`);
        }
        // console.log('All pages rendered');
    } catch (reason) {
        console.error('PDF loading/rendering error:', reason);
        if (reason.name === 'RenderingCancelledException' || reason.name === 'AbortException') {
            console.log('PDF rendering cancelled.');
             pdfViewerContainer.innerHTML = `<div class="p-4 text-yellow-700 bg-yellow-100 dark:bg-yellow-200 dark:text-yellow-800 rounded-lg">PDF loading cancelled.</div>`;
        } else {
            let fileName = pdfUrl.substring(pdfUrl.lastIndexOf('/') + 1);
            pdfViewerContainer.innerHTML = `<div class="p-4 text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800 rounded-lg">Error loading PDF "${fileName}": ${reason.message}. Please try selecting another file or ensure the file exists.</div>`;
        }
    } finally {
        currentLoadingTask = null;
        document.body.classList.remove('loading-pdf');
    }
}

function loadSelectedPdf() {
    if (pdfSelect && pdfSelect.value) {
        const pdfName = pdfSelect.value;
        renderPdf(`/pdf/${pdfName}`);
    } else if (!pdfUpload || !pdfUpload.value) { // Only load default if no upload is also selected
        const defaultSampleUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
        console.log('No PDF selected or dropdown not found, loading default sample.');
        renderPdf(defaultSampleUrl);
    }
}

function handlePdfUpload(event) {
    const file = event.target.files[0];
    if (file && file.type === "application/pdf") {
        const fileURL = URL.createObjectURL(file);
        if (pdfSelect) {
            pdfSelect.value = ''; // Deselect any item in the dropdown
        }
        renderPdf(fileURL);
        // Revoke object URL after processing to free up memory, though PDF.js might hold onto it
        // Consider revoking it when a new PDF is loaded or viewer is closed.
        // URL.revokeObjectURL(fileURL);
    } else {
        if(file) alert("Please select a valid PDF file.");
    }
}

window.addEventListener('load', () => {
    let initialPdfName = pdfViewerContainer ? pdfViewerContainer.dataset.defaultPdf : null;

    if (pdfSelect && pdfSelect.options.length > 0) {
        if (initialPdfName && Array.from(pdfSelect.options).some(opt => opt.value === initialPdfName)) {
            pdfSelect.value = initialPdfName;
        } else {
            initialPdfName = pdfSelect.options[0].value;
            pdfSelect.value = initialPdfName;
        }
        if(initialPdfName) renderPdf(`/pdf/${initialPdfName}`);
        else { // Fallback if initialPdfName is somehow empty even with options
            const defaultSampleUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
            console.log('No initial PDF name, loading default sample PDF.');
            renderPdf(defaultSampleUrl);
        }
        pdfSelect.addEventListener('change', loadSelectedPdf);

    } else if (initialPdfName) { // Case where select is empty but default is provided (e.g. only one file, no dropdown)
         renderPdf(`/pdf/${initialPdfName}`);
    }
    else { // Fallback if no PDF files are listed and no default from controller
        const defaultSampleUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
        console.log('No PDF files in dropdown or default, loading default sample PDF.');
        renderPdf(defaultSampleUrl);
    }

    if (pdfUpload) {
        pdfUpload.addEventListener('change', handlePdfUpload);
    }
});
