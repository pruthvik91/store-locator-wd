const puppeteer = require('puppeteer-core');
const fs = require('fs');
const path = require('path');

(async () => {
    const args = process.argv.slice(2);
    const url = args[0];
    const pdfPath = args[1];

    console.log(`URL: ${url}`);
    console.log(`PDF Path: ${pdfPath}`);

    if (!url || !pdfPath) {
        console.error('URL and PDF Path must be provided as arguments.');
        process.exit(1);
    }

    try {
        const browser = await puppeteer.launch({
            executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe',
        });

        const page = await browser.newPage();
        
        console.log(`Navigating to ${url}`);
        await page.goto(url, { waitUntil: 'networkidle2', timeout: 60000 });
        console.log(`Successfully navigated to ${url}`);

        // Ensure the directory exists
        const dir = path.dirname(pdfPath);
        if (!fs.existsSync(dir)) {
            fs.mkdirSync(dir, { recursive: true });
        }

        await page.pdf({
            path: pdfPath,
            format: 'A4',
            printBackground: true,
        });

        console.log(`PDF saved to ${pdfPath}`);
        await browser.close();
    } catch (error) {
        console.error('Error during Puppeteer execution:', error);
    }
})();
