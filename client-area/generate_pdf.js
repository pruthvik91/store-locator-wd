const puppeteer = require('puppeteer-core');

(async () => {
    const args = process.argv.slice(2);
    const url = args[0];
    const pdfPath = args[1];

    const browser = await puppeteer.launch({
        executablePath: 'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe',
    });

    const page = await browser.newPage();
    
    console.log(`Navigating to ${url}`);
    await page.goto(url, { waitUntil: 'networkidle2', timeout: 60000 }); // Increase timeout to 60 seconds
    console.log(`Successfully navigated to ${url}`);

    await page.pdf({
        path: pdfPath,
        format: 'A4',
        printBackground: true,
    });

    await browser.close();
})();
