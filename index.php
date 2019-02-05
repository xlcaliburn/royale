<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">

<style>

</style>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


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
                <th>Lifetime Wins</th>
                <th>Lifetime Attacks</th>
                <th>Misses</th>
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
								<td>";
                echo($members->{$key}->playerName);
                echo "</td>
								<td>$value</td>
								<td>".$clan->attacks[$key]."</td>";

                    if ($clan->attacks[$key] > 0 ) {
                      $winrate = round(($value/$clan->attacks[$key]*100),0);
                    }
                    else {
                      $winrate = 0;
                    }
                    if ($winrate <= 20) {
                      echo "<td class='vlow'>";
                    }
                    else if ($winrate <= 40) {
                      echo "<td class='low'>";
                    }
                    elseif ($winrate <= 60) {
                      echo "<td class='med'>";
                    }
                    elseif ($winrate <= 80) {
                      echo "<td class='high'>";
                    }
                    else {
                      echo "<td class='vhigh'>";
                    }
                			echo $winrate."%</td>
      <td>";
      $lifetimeWinrate = $members->{$key}->lifetimeBattles ? round(100*$members->{$key}->lifetimeWins/$members->{$key}->lifetimeBattles,1) : 0;

      echo $lifetimeWinrate < $winrate ? '<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>' : '<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>';
      echo " ".$lifetimeWinrate ."%";
      echo "</td><td>";
      echo $members->{$key}->lifetimeWins;
      echo "</td><td>";
      echo $members->{$key}->lifetimeBattles;
      echo "</td><td>";
      echo $members->{$key}->misses;
      echo "</tr>";
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
