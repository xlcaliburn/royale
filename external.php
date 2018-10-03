<?php
  require('connect.php');

  function curl_get_contents($url, $token)
  {
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

  $warlog = json_decode(curl_get_contents("https://api.royaleapi.com/clan/8GYYR/warlog", $token));

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
  $sql = "SELECT MAX(createdDate) AS 'Latest' FROM warlog";
  $result = $conn->query($sql);
  $latest = $result->fetch_assoc()["Latest"];

  foreach($warlog as $war) {
   foreach($war->participants as $participant) {
     if ($latest < $war->createdDate) {
       $sql = "INSERT INTO warlog (createdDate, playerTag, playerName, battlesPlayed, wins) VALUES (".
         $war->createdDate.",'".
         $participant->tag."','".
         $participant->name."',".
         $participant->battlesPlayed.",".
         $participant->wins.")";
       $result = $conn->query($sql);
     }

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

  $createdDate = $warlog[0]->createdDate;

?>
