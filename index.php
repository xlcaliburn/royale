<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
</head>

<body>
    <div class="container">
        <?php

  function curl_get_contents($url)
  {
      require('token.php');
      $header = ["auth:".$token];
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
  }

  $warlog = json_decode(curl_get_contents("https://api.royaleapi.com/clan/8GYYR/warlog"));

  $clanGraph = array();
  $clan = (object) array(
    "wins"=>array(),
    "attacks"=>array(),
    "missed"=>array(),
    "aggregated"=>(object) array(
      "vlow"=>0,
      "low"=>0,
      "med"=>0,
      "high"=>0,
      "vhigh"=>0
    )
  );

  foreach($warlog as $item) {
   foreach($item->participants as $participant) {
     if (isset($clan->attacks[$participant->name])) {
       $clan->wins[$participant->name] += $participant->wins;
       $clan->attacks[$participant->name] += $participant->battlesPlayed;
     }
     else {
       $clan->wins[$participant->name] = 1;
       $clan->attacks[$participant->name] = $participant->battlesPlayed;
     }
   }
  }

  echo "<h4>Total Participants in the past 10 wars: ".count($clan->attacks)."</h4>";
  echo "<h5>War Wins Overview</h5>";
  echo      '<div>
          <canvas id="chart" style="height:40vh"></canvas>
      </div>';

  echo "<table class='table'>";
  echo "
						<thead>
							<tr>
								<th>Name</th>
								<th>Wins</th>
								<th>Total Attacks</th>
								<th>Win Rate</th>
							</tr>
						</thead>";
var_dump($clan->aggregated->vlow);
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
								<td>".$clan->attacks[$key]."</td>
								<td>".round(($value/$clan->attacks[$key]*100),0)."%</td>
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
