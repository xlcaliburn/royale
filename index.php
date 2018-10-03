<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">

<style>

</style>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
</head>

<!-- As a link -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="#">
    <!-- <img src="/docs/4.0/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt=""> -->
    Toronto
  </a>
</nav>

<body>
    <div class="container">

      curl_setopt($ch, CURLOPT_HEADER, 0);
      <div class="row">
        <div class="col-md">
          <h1>Toronto - Clash Royale War Stats</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <h5>War Wins Overview</h5>
          <div><canvas id="chart" style="height:40vh"></canvas></div>
        </div>
      </div>
<?php
  require('external.php');

  echo "<h4>Total Participants in the past 10 wars: ".count($clan->attacks)."</h4>";
  echo "<table class='table'>";
  echo "
						<thead>
							<tr>
								<th>Name</th>
								<th>Wins</th>
								<th>Total Attacks</th>
								<th>Win Rate</th>
                <th>Lifetime Win Rate</th>
                <th>Streak</th>
							</tr>
						</thead>";

  array_multisort($clan->wins, SORT_DESC, $clan->attacks);

  foreach($clan->wins as $key=>$value){
    if ($value <= 2) {
      $clan->aggregated->vlow++;
    }
    else if ($value <=4) {
      $clan->aggregated->low++;
    }
    else if ($value <= 6) {
      $clan->aggregated->med++;
    }
    else if ($value <=8) {
      $clan->aggregated->high++;
    }
    else {
      $clan->aggregated->vhigh++;
    }

     echo "
              <tr>
								<td>$key</td>
								<td>$value</td>
								<td>".$clan->attacks[$key]."</td>";

    $winrate = round(($value/$clan->attacks[$key]*100),0);
    if ($winrate < 50) {
      echo "<td class='vlow'>";
    }
    elseif ($winrate < 75) {
      echo "<td class='med'>";
    }
    else {
      echo "<td class='vhigh'>";
    }
			echo $winrate."%</td>
							</tr>";
  }
  echo "

						</table>";
?>

    </div>

    <script>
      var ctx = document.getElementById("chart").getContext('2d');
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: ["0-2", "3-4", "5-6", "7-8", "9-10"],
              datasets: [{
                  data: [<?php echo $clan->aggregated->vlow.",".$clan->aggregated->low.",".$clan->aggregated->med.",".$clan->aggregated->high.",".$clan->aggregated->vhigh; ?>],
                  label: 'War Wins',
                  backgroundColor: [
                      'rgba(255, 40, 0, 0.8)',
                      'rgba(255, 127, 0, 0.8)',
                      'rgba(255, 240, 0, 0.8)',
                      'rgba(206, 255, 0, 0.8)',
                      'rgba(0, 212, 0 , 0.8)'
                  ],
                  borderColor: [
                    'rgba(255, 40, 0, 1)',
                    'rgba(255, 127, 0, 1)',
                    'rgba(255, 240, 0, 1)',
                    'rgba(206, 255, 0, 1)',
                    'rgba(0, 212, 0 , 1)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero:true
                      }
                  }]
              }
          }
      });
    </script>

</body>

</html>
