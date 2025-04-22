<?php
/**
 * 3D Print Calculator
 * Modern PHP implementation with Bootstrap Vue
 */

// Default values
$defaultHourlyRate = 25;
$defaultMaterialPrice = 700;

// Process AJAX request if needed
if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
    // Get input values
    $hours = isset($_POST['hours']) ? floatval($_POST['hours']) : 0;
    $minutes = isset($_POST['minutes']) ? floatval($_POST['minutes']) : 0;
    $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
    $hourlyRate = isset($_POST['hourlyRate']) ? floatval($_POST['hourlyRate']) : $defaultHourlyRate;
    $materialPrice = isset($_POST['materialPrice']) ? floatval($_POST['materialPrice']) : $defaultMaterialPrice;
    $electricityPrice = isset($_POST['electricityPrice']) ? floatval($_POST['electricityPrice']) : 6.5;
    $printerPower = isset($_POST['printerPower']) ? floatval($_POST['printerPower']) : 0.3;

    // Calculate total print time in hours
    $totalHours = $hours + ($minutes / 60);

    // Calculate costs
    $materialCost = ceil(($weight / 1000) * $materialPrice);
    $printingCost = ceil($totalHours * $hourlyRate);
    $electricityCost = ceil($totalHours * $printerPower * $electricityPrice);
    $totalCost = $materialCost + $printingCost + $electricityCost;

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'materialCost' => $materialCost,
        'printingCost' => $printingCost,
        'electricityCost' => $electricityCost,
        'totalCost' => $totalCost
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Výpočet ceny 3D tisku pomocí kalkulátoru">
    <meta name="author" content="Valentino Hesse OK2HSS pro web hardwired.dev a ok2hss.cz">
    <meta name="copyright" content="© 2023 OK2HSS HARDWIRED.dev">
    <title>Kalkulátor 3D Tisk</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Vue CSS -->
    <link href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="app" class="container">
        <div class="calculator-container">
            <div class="logo-container">
                <img src="logo_big.svg" alt="3D Tisk Logo" class="img-fluid mb-3">
                <div>
                    <a href="https://www.OK2HSS.cz" target="_blank"><i class="fas fa-globe me-1"></i>www.ok2hss.cz</a> //
                    <a href="https://www.hardwired.dev" target="_blank"><i class="fas fa-globe me-1"></i>www.hardwired.dev</a>
                </div>
            </div>

            <h2 class="text-center mb-4">3D Tisk Kalkulátor</h2>

            <b-form @submit.prevent="calculate">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="printHours">Čas tisku (hodiny):</label>
                            <b-form-input
                                id="printHours"
                                v-model="printHours"
                                type="number"
                                min="0"
                                class="form-control"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Zadejte počet hodin tisku">
                            </b-form-input>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="printMinutes">Čas tisku (minuty):</label>
                            <b-form-input
                                id="printMinutes"
                                v-model="printMinutes"
                                type="number"
                                min="0"
                                max="59"
                                class="form-control"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Zadejte počet minut tisku">
                            </b-form-input>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="materialWeight">Váha výtisku (g):</label>
                    <b-form-input
                        id="materialWeight"
                        v-model="materialWeight"
                        type="number"
                        min="0"
                        class="form-control"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Zadejte váhu výtisku v gramech">
                    </b-form-input>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hourlyRate">Cena času (Kč/hod):</label>
                            <b-form-input
                                id="hourlyRate"
                                v-model="hourlyRate"
                                type="number"
                                min="0"
                                class="form-control"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Zadejte hodinovou sazbu tisku">
                            </b-form-input>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="materialPrice">Cena materiálu (Kč/kg):</label>
                            <b-form-input
                                id="materialPrice"
                                v-model="materialPrice"
                                type="number"
                                min="0"
                                class="form-control"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Zadejte cenu materiálu za kilogram">
                            </b-form-input>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="electricityPrice">Cena elektřiny (Kč/kWh):</label>
                            <b-form-input
                                id="electricityPrice"
                                v-model="electricityPrice"
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Zadejte cenu elektřiny za kilowatthodinu">
                            </b-form-input>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="printerPower">Příkon tiskárny (kW):</label>
                            <b-form-input
                                id="printerPower"
                                v-model="printerPower"
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Zadejte příkon 3D tiskárny v kilowattech">
                            </b-form-input>
                        </div>
                    </div>
                </div>

                <b-button
                    type="submit"
                    variant="primary"
                    class="btn-calculate"
                    :disabled="calculating">
                    <i class="fas fa-calculator me-2"></i>
                    <span v-if="!calculating">Spočítat cenu tisku</span>
                    <span v-else>
                        <b-spinner small></b-spinner> Počítám...
                    </span>
                </b-button>
            </b-form>

            <transition name="fade">
                <div v-if="resultsVisible" class="results-container" :class="{ 'fade-in': animateResults }">
                    <div class="result-item material-cost">
                        <div class="result-label">
                            <i class="fas fa-box me-2"></i> Cena materiálu:
                        </div>
                        <div class="result-value">{{ materialCost }} Kč</div>
                    </div>

                    <div class="result-item print-cost">
                        <div class="result-label">
                            <i class="fas fa-clock me-2"></i> Cena tisku (bez materiálu):
                        </div>
                        <div class="result-value">{{ printingCost }} Kč</div>
                    </div>

                    <div class="result-item electricity-cost">
                        <div class="result-label">
                            <i class="fas fa-bolt me-2"></i> Cena elektřiny:
                        </div>
                        <div class="result-value">{{ electricityCost }} Kč</div>
                    </div>

                    <div class="result-item total-cost">
                        <div class="result-label">
                            <i class="fas fa-tag me-2"></i> Celková cena:
                        </div>
                        <div class="result-value">{{ totalCost }} Kč</div>
                    </div>
                </div>
            </transition>


        </div>

        <footer class="text-center mt-4 mb-5">
            <p class="text-secondary">
                &copy; <?php echo date('Y'); ?> OK2HSS HARDWIRED.dev | 3D Tisk Kalkulátor
            </p>
        </footer>
    </div>

    <!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Vue -->
    <script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="js/calculator.js"></script>
</body>
</html>
