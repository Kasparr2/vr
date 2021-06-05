<?php
    class Test{
        //class muutujad ehk propertes
        private $secret = 7;
        public  $non_secret = 3;
        private $received_secret;

        //class funktsioonid ehk meetodid(methods)
        function __construct($received) {
            echo "klass on laetud, konstruktor töötab";
            $this->received_secret = $received;
            echo "Saabunud salajane number on: " .$this->received_secret;
        }

        function __destruct() {
            echo "Klass lõpetas!";
        }

        public function reveal() {
            echo "Täiesti salajane number on:". $this->secret .".";
        }
    } //class lõppeb