<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('utilisateur')){
          Schema::create('utilisateur', function($table)
          {
			$table->increments('UtilisateurID');
			$table->string('Username')->unique();
			$table->string('Mail')->nullable();
			$table->string('password');
			$table->string('nom')->nullable();
			$table->string('prenom')->nullable();
			$table->string('telephone')->nullable();
			$table->string('isadmin', 1)->default(0);
			$table->string('remember_token', 100)->nullable();
          });
		}
		
		if(!Schema::hasTable('roles')){
	      Schema::create('roles', function($table)
	      {
	        $table->increments('RoleID');
	        $table->string('Username');
			$table->enum('Role', ['OPERATEUR','SUPERUTILISATEUR','ALERT','AGRICULTEUR','ACHETEUR','PARTENAIRE', 'RECOLTE', 'NEGOCIATIONRECOLTE'])
				->default('OPERATEUR');
	      });
		  Schema::table('roles', function($table){
			$table->foreign('Username')->references('Username')->on('utilisateur')->onDelete('cascade')->onUpdate('cascade');
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
		Schema::dropIfExists('roles');
		Schema::dropIfExists('utilisateur');
	}

}
