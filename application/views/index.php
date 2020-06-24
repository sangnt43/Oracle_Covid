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

    <!-- Data  -->
    <!-- <script src="<?= base_url("public/data/js/world_timeline.js") ?>"></script>
    <script src="<?= base_url("public/data/js/total_timeline.js") ?>"></script> -->

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

<script>
    fetch("<?= base_url("Countries/GetAll") ?>",{
        headers: {
            "HTTP_X_REQUESTED_WITH" : "AJAX"
        }
    }).then(b=> b.json())
        .then(b=> {
            // covid_world_timeline = (b);
        })
    fetch("<?= base_url("Covids/GetAll") ?>",{
        headers: {
            "HTTP_X_REQUESTED_WITH" : "AJAX"
        }
    }).then(b=> b.json())
        .then(b=> {
            covid_world_timeline = (b);
            createGraph();
        })
    fetch("<?= base_url("Globals/GetAll") ?>",{
        headers: {
            "HTTP_X_REQUESTED_WITH" : "AJAX"
        }
    }).then(b=> b.json())
        .then(b=> {
            console.log(b);
        })

        
</script>
<!-- Main app -->
<script src="<?= base_url("public/app.js") ?>"></script>

</html>