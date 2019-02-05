<?php
  require('connect.php');
  require('member.php');

  $warlog = json_decode(curl_get_contents("https://api.royaleapi.com/clan/PCULPL2Q/warlog", $token));

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

  $sql = "SELECT playerTag, playerName, SUM(battlesPlayed) as 'lifetimeBattles', SUM(wins) AS 'lifetimeWins', sum(if(battlesPlayed = 0, 1, 0)) AS 'misses' FROM warlog GROUP BY playerTag";
  $result = $conn->query($sql);
  $members = (object)[];
  while($row = $result->fetch_assoc()) {
    $name = $row["playerTag"];
    $members->$name = new Member($row["playerTag"], $row["playerName"], $row["lifetimeWins"], $row["lifetimeBattles"], $row["misses"]);
  }

  foreach($warlog as $war) {
   foreach($war->participants as $participant) {
     if ($latest < $war->createdDate) {

       $sql = "INSERT INTO warlog (createdDate, playerTag, playerName, battlesPlayed, wins) VALUES (".
         $war->createdDate.",'".
         $participant->tag."','".
         str_replace("'", "", $participant->name)  ."',".
         $participant->battlesPlayed.",".
         $participant->wins.")";
       $result = $conn->query($sql);
     }

     if (isset($clan->attacks[$participant->tag])) {

       $clan->wins[$participant->tag] += $participant->wins;
       $clan->attacks[$participant->tag] += $participant->battlesPlayed;
     }
     else {
       $clan->wins[$participant->tag] = $participant->wins;
       $clan->attacks[$participant->tag] = $participant->battlesPlayed;
     }

   }
  }

  $createdDate = $warlog[0]->createdDate;

?>
