/**
 * ArtVision - Main JavaScript File
 * This file handles all the interactive features of the website.
 * 
 * Features:
 * 1. Theme Toggle (Dark/Light mode)
 * 2. Cursor Following Blob
 * 3. Background Blob Animations
 * 4. Price Calculator
 */

// Wait for the page to fully load before running any code
document.addEventListener('DOMContentLoaded', function () {

    // ============================================
    // SECTION 1: THEME TOGGLE (Dark/Light Mode)
    // ============================================

    // Find the toggle button in the HTML
    var toggleButton = document.getElementById('theme-toggle');

    // Check if user previously selected a theme (saved in browser)
    var savedTheme = localStorage.getItem('artvision-theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }

    // When the button is clicked, switch themes
    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            // Toggle the 'light-mode' class on the body
            document.body.classList.toggle('light-mode');

            // Save the preference so it persists across pages
            if (document.body.classList.contains('light-mode')) {
                localStorage.setItem('artvision-theme', 'light');
            } else {
                localStorage.setItem('artvision-theme', 'dark');
            }
        });
    }


    // ============================================
    // SECTION 1.5: BURGER MENU (Mobile Navigation)
    // ============================================

    // Find the burger button and nav menu
    var burgerButton = document.getElementById('burger-menu');
    var navMenu = document.getElementById('nav-menu');

    // When burger is clicked, toggle the menu
    if (burgerButton && navMenu) {
        burgerButton.addEventListener('click', function () {
            // Toggle 'active' class on both burger and menu
            burgerButton.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close menu when a link is clicked
        var navLinks = navMenu.querySelectorAll('.nav-link');
        for (var i = 0; i < navLinks.length; i++) {
            navLinks[i].addEventListener('click', function () {
                burgerButton.classList.remove('active');
                navMenu.classList.remove('active');
            });
        }
    }


    // ============================================
    // SECTION 2: CURSOR FOLLOWING BLOB
    // ============================================

    // Find the cursor blob element
    var cursorBlob = document.getElementById('cursor-blob');

    if (cursorBlob) {
        // Hide it initially (off-screen)
        cursorBlob.style.left = '-300px';
        cursorBlob.style.top = '-300px';

        // When the mouse moves, update the blob position
        document.addEventListener('mousemove', function (event) {
            // Get mouse coordinates
            var mouseX = event.clientX;
            var mouseY = event.clientY;

            // Move the blob to follow the mouse
            cursorBlob.style.left = mouseX + 'px';
            cursorBlob.style.top = mouseY + 'px';
        });
    }


    // ============================================
    // SECTION 3: BACKGROUND BLOB SCROLL ANIMATION
    // ============================================

    // Find the two background blobs
    var blob1 = document.querySelector('.blob-1'); // Blue blob (top-left)
    var blob2 = document.querySelector('.blob-2'); // Pink blob (bottom-right)

    if (blob1 && blob2) {

        // When user scrolls the page, move the blobs
        window.addEventListener('scroll', function () {
            // Calculate how far down the page we've scrolled (0 to 1)
            var scrollPosition = window.scrollY;
            var pageHeight = document.body.scrollHeight - window.innerHeight;
            var scrollPercent = scrollPosition / pageHeight;

            // Make sure it stays between 0 and 1
            if (scrollPercent > 1) scrollPercent = 1;
            if (scrollPercent < 0) scrollPercent = 0;

            // Calculate new positions for blobs
            // As you scroll down: blob1 goes DOWN, blob2 goes UP
            var screenHeight = window.innerHeight;
            var blob1Position = -100 + (scrollPercent * screenHeight);
            var blob2Position = screenHeight - 300 - (scrollPercent * screenHeight);

            // Apply the new positions
            blob1.style.top = blob1Position + 'px';
            blob2.style.top = blob2Position + 'px';
            blob2.style.bottom = 'auto'; // Override the CSS bottom property
        });
    }
});


// ============================================
// SECTION 4: PRICE CALCULATOR (Product Page)
// ============================================

// This function runs when the user changes license type
function updatePrice() {
    // Get the base price from the hidden input
    var basePrice = document.getElementById('base-price');
    var priceDisplay = document.getElementById('final-price');
    var priceBgn = document.getElementById('price-bgn');
    var eurToBgnRate = document.getElementById('eur-to-bgn');

    if (!basePrice || !priceDisplay) return; // Exit if not on product page

    // Get conversion rate (default to 1.9558 if not found)
    var conversionRate = eurToBgnRate ? parseFloat(eurToBgnRate.value) : 1.9558;

    // Get the selected license type
    var licenseInputs = document.getElementsByName('license');
    var selectedLicense = 'personal';

    for (var i = 0; i < licenseInputs.length; i++) {
        if (licenseInputs[i].checked) {
            selectedLicense = licenseInputs[i].value;
        }
    }

    // Calculate the final price in EUR
    var finalPriceEur = parseFloat(basePrice.value);

    if (selectedLicense === 'commercial') {
        finalPriceEur = finalPriceEur * 3; // Commercial license costs 3x more
    }

    // Calculate BGN price
    var finalPriceBgn = Math.round(finalPriceEur * conversionRate);

    // Animate the EUR price change
    var currentPrice = parseFloat(priceDisplay.innerText) || 0;
    var priceDiff = finalPriceEur - currentPrice;
    var duration = 2000; // Animation duration in ms
    var startTime = null;

    // Add scale animation
    priceDisplay.style.transition = 'transform 0.15s ease';
    priceDisplay.style.transform = 'scale(1.1)';

    function animatePrice(timestamp) {
        if (!startTime) startTime = timestamp;
        var progress = Math.min((timestamp - startTime) / duration, 1);

        // Ease-out with power of 20 for maximum tension at the end
        var easeOut = 1 - Math.pow(1 - progress, 20);

        // Calculate current animated value
        var animatedPrice = currentPrice + (priceDiff * easeOut);
        priceDisplay.innerText = Math.round(animatedPrice);

        // Also update BGN display
        if (priceBgn) {
            var animatedBgn = Math.round(animatedPrice * conversionRate);
            priceBgn.innerHTML = '≈ ' + animatedBgn + ' лв.';
        }

        if (progress < 1) {
            requestAnimationFrame(animatePrice);
        } else {
            priceDisplay.innerText = Math.round(finalPriceEur);
            if (priceBgn) {
                priceBgn.innerHTML = '≈ ' + finalPriceBgn + ' лв.';
            }
            // Reset scale
            priceDisplay.style.transform = 'scale(1)';
        }
    }

    requestAnimationFrame(animatePrice);
}


// ============================================
// SECTION 5: UPDATE BUY LINK (Product Page)
// ============================================

// This function runs when user changes the format (JPG/SVG)
function updateBuyLink() {
    var buyLink = document.getElementById('buy-link');
    var productId = document.getElementById('product-id');

    if (!buyLink || !productId) return;

    // Get the selected format
    var formatInputs = document.getElementsByName('format');
    var selectedFormat = 'jpg';

    for (var i = 0; i < formatInputs.length; i++) {
        if (formatInputs[i].checked) {
            selectedFormat = formatInputs[i].value;
        }
    }

    // Update the link with the selected format
    buyLink.href = 'download.php?id=' + productId.value + '&format=' + selectedFormat;
}
