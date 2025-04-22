/**
 * 3D Print Calculator
 * Modern JavaScript implementation with animations
 */

// Vue application
const app = new Vue({
    el: '#app',
    data: {
        // Input values
        printHours: 0,
        printMinutes: 0,
        materialWeight: 0,
        hourlyRate: 25,
        materialPrice: 700,
        electricityPrice: 6.5, // Cena elektriny v Kč/kWh
        printerPower: 0.3, // Príkon tlačiarne v kW (predvolená hodnota)

        // Results
        materialCost: 0,
        printingCost: 0,
        electricityCost: 0,
        totalCost: 0,

        // UI states
        calculating: false,
        resultsVisible: false,
        animateResults: false
    },
    methods: {
        /**
         * Calculate all costs
         */
        calculate() {
            // Start calculation animation
            this.calculating = true;
            this.animateButton();

            // Simulate calculation delay for animation effect
            setTimeout(() => {
                // Convert inputs to numbers and handle invalid values
                const hours = parseFloat(this.printHours) || 0;
                const minutes = parseFloat(this.printMinutes) || 0;
                const weight = parseFloat(this.materialWeight) || 0;
                const hourlyRate = parseFloat(this.hourlyRate) || 0;
                const materialPrice = parseFloat(this.materialPrice) || 0;
                const electricityPrice = parseFloat(this.electricityPrice) || 0;
                const printerPower = parseFloat(this.printerPower) || 0;

                // Calculate total print time in hours
                const totalHours = hours + (minutes / 60);

                // Calculate costs
                this.materialCost = Math.ceil((weight / 1000) * materialPrice);
                this.printingCost = Math.ceil(totalHours * hourlyRate);
                this.electricityCost = Math.ceil(totalHours * printerPower * electricityPrice);
                this.totalCost = this.materialCost + this.printingCost + this.electricityCost;

                // Show results with animation
                this.resultsVisible = true;
                this.calculating = false;
                this.animateResults = true;

                // Reset animation flag after animation completes
                setTimeout(() => {
                    this.animateResults = false;
                }, 500);

            }, 500); // Delay for animation effect
        },

        /**
         * Animate the calculate button
         */
        animateButton() {
            const button = document.querySelector('.btn-calculate');
            button.classList.add('pulse');

            // Remove animation class after animation completes
            setTimeout(() => {
                button.classList.remove('pulse');
            }, 500);
        },

        /**
         * Format number as currency
         */
        formatCurrency(value) {
            return value.toLocaleString('cs-CZ') + ' Kč';
        }
    }
});

/**
 * Initialize tooltips and other Bootstrap components
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
