<?php
    class Recolte extends Eloquent
    {
      protected $table = 'recolte';

      protected $primaryKey = 'RecolteID';

      public function __construct() {
        parent::__construct();

        //Set the format used by Carbon when converting date to string
        \Carbon\Carbon::setToStringFormat('d-m-Y');
        //all dates like 2014-03-25 17:37:54 look like 25-03-2014 17:37:54 now
      }

      public function Agriculteur()
      {
        return $this->belongsTo('Agriculteur', 'AgriculteurID');
      }

      public function Produit()
      {
        return $this->belongsTo('Produit', 'ProduitID');
      }

      public function getDatesoumissionFAttribute(){
        $dt = new \Carbon\Carbon($this->attributes['DateSoumission']);
        return $dt->format('d/m/Y');
      }

      public function getEditUrlAttribute()
      {
        return URL::to('recolte/' .  $this->attributes['RecolteID'] . '/edit');
      }

      public function getDeleteUrlAttribute()
      {
          return URL::to('recolte/' . $this->attributes['RecolteID'] );
      }

      protected $appends = array('datesoumission_f', 'edit_url', 'delete_url');
    }