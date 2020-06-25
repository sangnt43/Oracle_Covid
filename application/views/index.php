<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- amCharts includes -->
    <script src="<?= base_url("public/vendors/amcharts4/core.js") ?>"></script>
    <script src="<?= base_url("public/vendors/amcharts4/charts.js") ?>"></script>
    <script src="<?= base_url("public/vendors/amcharts4/maps.js") ?>"></script>

    <script src="<?= base_url("public/vendors/amcharts4/themes/dark.js") ?>"></script>
    <script src="<?= base_url("public/vendors/amcharts4/themes/animated.js") ?>"></script>

    <script src="<?= base_url("public/vendors/amcharts4-geodata/worldLow.js") ?>"></script>
    <script src="<?= base_url("public/vendors/amcharts4-geodata/data/countries2.js") ?>"></script>

    <!-- DataTables includes -->
    <script src="<?= base_url("public/vendors/jquery/jquery-3.3.1.min.js") ?>"></script>
    <link rel="stylesheet" media="all" href="<?= base_url("public/vendors/datatables/css/jquery.dataTables.min.css") ?>" />
    <link rel="stylesheet" media="all" href="<?= base_url("public/vendors/datatables/css/select.dataTables.min.css") ?>" />
    <script src="<?= base_url("public/vendors/datatables/js/jquery.dataTables.min.js") ?>"></script>
    <script src="<?= base_url("public/vendors/datatables/js/dataTables.select.min.js") ?>"></script>

    <!-- Stylesheet -->
    <link rel="stylesheet" media="all" href="<?= base_url("public/dark.css") ?>" />
</head>


<body>
    <div class="flexbox">
        <div id="chartdiv"></div>
        <div id="list">
            <table id="areas" class="compact hover order-column row-border">
                <thead>
                    <tr>
                        <th>Country/State</th>
                        <th>Confirmed</th>
                        <th>Deaths</th>
                        <th>Recovered</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</body>

<script src="<?= base_url("public/app.js") ?>"></script>

<script>
    function finishLoading() {
        if (!window.countries || !window.covid_world_timeline || !window.covid_total_timeline)
            return;
        Start();
    }

    function callData(url, callback) {
        fetch(url, {
                headers: {
                    "HTTP_X_REQUESTED_WITH": "AJAX"
                }
            })
            .then(b => b.json()).then(b => {
                callback(b);
                finishLoading();
            });
    }

    window.addEventListener("DOMContentLoaded", async () => {
        callData("<?= base_url("Countries/GetAll") ?>", e => window.countries = e)
        callData("<?= base_url("Covids/GetAll") ?>", e => window.covid_world_timeline = e)
        callData("<?= base_url("Globals/GetAll") ?>", e => window.covid_total_timeline = e)
    })
</script>

</html>