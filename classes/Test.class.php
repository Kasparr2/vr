<?php
    class Test{
        //class muutujad ehk propertes
        private $secret = 7;
        public  $non_secret = 3;
        private $received_secret;

        //class funktsioonid ehk meetodid(methods)
        function __construct($received) {           //Käivitub sellel hetkel kui class kasutusele võetakse.
            echo "Klass on laetud, konstruktor töötab! ";
            $this->received_secret = $received;
            echo " Saabunud salajane number on " .$this->received_secret .". ";
            $this->multiplay();
        }

        function __destruct() { 	    //Lõpetab konstruktori.
            echo " Klass lõpetas! ";
        }

        public function reveal() {
            echo " Täiesti salajane number on " .$this->secret .". ";
        }

        private function multiplay(){
            echo " Korrutis on: " .$this->secret * $this->non_secret * $this->received_secret .". ";
        }
    } //class lõppeb