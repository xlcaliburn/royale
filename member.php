<?php
  class Member {
    public $playerTag;
    public $playerName;
    public $lifetimeWins;
    public $lifetimeBattles;
    public $misses;
    public $wins;
    public $battles;

    function __construct($playerTag, $playerName, $lifetimeWins, $lifetimeBattles, $misses) {
       $this->playerTag = $playerTag;
       $this->playerName = $playerName;
       $this->lifetimeWins = $lifetimeWins;
       $this->lifetimeBattles = $lifetimeBattles;
       $this->misses = $misses;
    }

    function getLifetimeWinrate () {
      return $this->lifetimeBattles ? $this->lifetimeWins/$this->lifetimeBattles : 0;
    }
  }

?>
