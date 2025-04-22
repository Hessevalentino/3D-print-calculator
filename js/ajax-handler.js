/**
 * AJAX Handler for 3D Print Calculator
 * Provides alternative calculation method using server-side processing
 */

// Use this function if you prefer server-side calculation instead of client-side
function calculateViaAjax() {
    // Get form values
    const hours = parseFloat(app.printHours) || 0;
    const minutes = parseFloat(app.printMinutes) || 0;
    const weight = parseFloat(app.materialWeight) || 0;
    const hourlyRate = parseFloat(app.hourlyRate) || 0;
    const materialPrice = parseFloat(app.materialPrice) || 0;
    const electricityPrice = parseFloat(app.electricityPrice) || 0;
    const printerPower = parseFloat(app.printerPower) || 0;

    // Start calculation animation
    app.calculating = true;

    // Prepare form data
    const formData = new FormData();
    formData.append('ajax', 'true');
    formData.append('hours', hours);
    formData.append('minutes', minutes);
    formData.append('weight', weight);
    formData.append('hourlyRate', hourlyRate);
    formData.append('materialPrice', materialPrice);
    formData.append('electricityPrice', electricityPrice);
    formData.append('printerPower', printerPower);

    // Send AJAX request
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Update results
        app.materialCost = data.materialCost;
        app.printingCost = data.printingCost;
        app.electricityCost = data.electricityCost;
        app.totalCost = data.totalCost;

        // Show results with animation
        app.resultsVisible = true;
        app.calculating = false;
        app.animateResults = true;

        // Reset animation flag after animation completes
        setTimeout(() => {
            app.animateResults = false;
        }, 500);
    })
    .catch(error => {
        console.error('Error:', error);
        app.calculating = false;
        alert('Došlo k chybě při výpočtu. Zkuste to prosím znovu.');
    });
}
