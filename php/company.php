<?php
   class Company {
      /* Member variables */
      var $name;
      var $ticker;
      var $eliasDistance;
      

   function __construct($par1, $par2, $par3) {
      $this->name = $par1;
      $this->ticker = $par2;
      $this->eliasDistance = $par3;
   }

      /* Member functions getters & setters */
      function setName($par){
         $this->name = $par;
      }
      
      function getName(){
         return $this->name;
      }
      
      function setTicker($par){
         $this->ticker = $par;
      }
      
      function getTicker(){
         return $this->ticker;
      }
     
      function setEliasDistance($par){
         $this->eliasDistance = $par;
      }
      
      function getEliasDistance(){
         return $this->eliasDistance;
      }


   }
?>