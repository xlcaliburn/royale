<?php
  class Member {
    public $playerTag;
    public $playerName;
    public $lifetimeWins;
    public $lifetimeBattles;
    public $wins;
    public $battles;

    function __construct($playerTag, $playerName, $lifetimeWins, $lifetimeBattles) {
       $this->playerTag = $playerTag;
       $this->playerName = $playerName;
       $this->lifetimeWins = $lifetimeWins;
       $this->lifetimeBattles = $lifetimeBattles;
    }
  }


?>
