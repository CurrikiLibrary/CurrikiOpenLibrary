<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable(<?php echo json_encode($subjectsChart); ?>);

        var options = {
            title: 'Deleted Resources By Subjects',
            backgroundColor: 'transparent'
        };

        var chart = new google.visualization.PieChart(document.getElementById('subjectchart'));

        chart.draw(data, options);

        <?php
            $i = 0;

            foreach ($subjectsData as $subjectName => $subjectData) {
                ?>
                data = google.visualization.arrayToDataTable(<?php echo json_encode($subjectData[1]); ?>);

                options = {
                    title: 'Deleted Resources By <?php echo $subjectName; ?>',
                    backgroundColor: 'transparent'
                };

                chart = new google.visualization.PieChart(document.getElementById('subjectchart<?php echo $i; ?>'));

                chart.draw(data, options);
                <?php

                $i++;
            }
        ?>

        data = google.visualization.arrayToDataTable(<?php echo json_encode($educationlevelsChart); ?>);

        options = {
            title: 'Deleted Resources By Education Levels',
            backgroundColor: 'transparent'
        };

        chart = new google.visualization.PieChart(document.getElementById('educationlevelchart'));

        chart.draw(data, options);

        <?php
            $i = 0;

            foreach ($educationlevelsData as $educationlevelName => $educationlevelData) {
                ?>
                data = google.visualization.arrayToDataTable(<?php echo json_encode($educationlevelData[1]); ?>);

                options = {
                    title: 'Deleted Resources By <?php echo $educationlevelName; ?>',
                    backgroundColor: 'transparent'
                };

                chart = new google.visualization.PieChart(document.getElementById('educationlevelchart<?php echo $i; ?>'));

                chart.draw(data, options);
                <?php

                $i++;
            }
        ?>
    }
</script>
<div class="wrap">
    <div id="icon-users" class="icon32"><br /></div>
    <h2>Delete Analytics</h2>
    <div>
        <div style="float:left;">
            <div id="subjectchart" style="width: 650px; height: 360px;"></div>
            <?php
                $i = 0;

                foreach ($subjectsData as $subjectData) {
                    ?>
                    <div id="subjectchart<?php echo $i; ?>" style="width: 650px; height: 360px;"></div>
                    <?php

                    $i++;
                }
            ?>
        </div>
        <div style="float:left;">
            <div id="educationlevelchart" style="width: 650px; height: 360px;"></div>
            <?php
                $i = 0;

                foreach ($educationlevelsData as $educationlevelData) {
                    ?>
                    <div id="educationlevelchart<?php echo $i; ?>" style="width: 650px; height: 360px;"></div>
                    <?php

                    $i++;
                }
            ?>
        </div>
    </div>
</div>
