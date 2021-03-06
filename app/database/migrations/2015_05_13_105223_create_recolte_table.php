<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecolteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('produit')){
	      Schema::create('produit', function($table)
	      {
	        $table->increments('ProduitID');
	        $table->string('Ref', 200);
			$table->string('Nom', 2000);
			$table->timestamps();
	      });
	    }
		
		if(!Schema::hasTable('recolte')){
	      Schema::create('recolte', function($table)
	      {
	        $table->increments('RecolteID');
	        $table->double('Poids', 15, 2);
			$table->integer('ProduitID')->unsigned();
			$table->integer('AgriculteurID')->unsigned();
			$table->date('DateSoumission');
			$table->enum('StatutSoumission', ['SOUMIS', 'VALIDE']);
			$table->enum('CanalSoumission', ['INTERNET', 'SMS', 'TELEPHONE']);
			$table->integer('InitiateurID')->unsigned();
			$table->timestamps();
	      });
		  
		  Schema::table('recolte', function($table){
			$table->foreign('ProduitID')->references('ProduitID')->on('produit');
			$table->foreign('AgriculteurID')->references('UtilisateurID')->on('utilisateur');
			$table->foreign('InitiateurID')->references('UtilisateurID')->on('utilisateur');
			
		  });
	    }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('recolte');
		Schema::dropIfExists('produit');
	}

}
