<?php

namespace extras\plugins\domainmapping\database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait MigrationsTrait
{
	/**
	 * @return void
	 */
	private static function migrationsInstall(): void
	{
		Schema::disableForeignKeyConstraints();
		
		Schema::dropIfExists('domains');
		Schema::create('domains', function (Blueprint $table) {
			$table->increments('id');
			$table->string('country_code', 2)->default('');
			$table->string('host', 255)->default('');
			$table->boolean('https')->default(false);
			$table->string('logo', 255)->nullable();
			$table->string('name', 200)->nullable();
			$table->boolean('active')->default(true);
			$table->timestamps();
			
			$table->index('country_code');
			$table->index('host');
		});
		
		Schema::dropIfExists('domain_settings');
		Schema::create('domain_settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('country_code', 2)->default('');
			$table->string('key', 255);
			$table->string('name', 255);
			$table->text('field')->nullable();
			$table->text('value')->nullable();
			$table->string('description', 500)->nullable();
			$table->integer('parent_id')->unsigned()->nullable();
			$table->integer('lft')->unsigned()->nullable();
			$table->integer('rgt')->unsigned()->nullable();
			$table->integer('depth')->unsigned()->nullable();
			$table->boolean('active')->nullable()->default(true);
			$table->timestamps();
			
			$table->unique(['country_code', 'key']);
			$table->index(['country_code']);
			$table->index(['key']);
			$table->index(['lft']);
			$table->index(['rgt']);
			$table->index(['active']);
		});
		
		Schema::dropIfExists('domain_meta_tags');
		Schema::create('domain_meta_tags', function (Blueprint $table) {
			$table->increments('id');
			$table->string('country_code', 2);
			$table->string('page', 50)->nullable();
			$table->text('title')->nullable();
			$table->text('description')->nullable();
			$table->text('keywords')->nullable();
			$table->boolean('active')->default(true);
			$table->timestamps();
			
			$table->index(['country_code']);
			$table->index(['page']);
			$table->index(['active']);
		});
		
		Schema::dropIfExists('domain_sections');
		Schema::create('domain_sections', function (Blueprint $table) {
			$table->increments('id');
			$table->string('country_code', 2);
			$table->string('belongs_to', 100)->default('home');
			$table->string('key', 200)->default('');
			$table->string('name', 100);
			$table->text('field')->nullable();
			$table->text('value')->nullable();
			$table->string('description', 500)->nullable();
			$table->integer('parent_id')->unsigned()->nullable();
			$table->integer('lft')->unsigned()->nullable();
			$table->integer('rgt')->unsigned()->nullable();
			$table->integer('depth')->unsigned()->nullable();
			$table->boolean('active')->nullable()->default(false);
			$table->timestamps();
			
			$table->unique(['country_code', 'belongs_to', 'key']);
			$table->index(['country_code']);
			$table->index(['belongs_to']);
			$table->index(['key']);
			$table->index(['lft']);
			$table->index(['rgt']);
			$table->index(['active']);
		});
		
		Schema::enableForeignKeyConstraints();
	}
	
	/**
	 * @return void
	 */
	private static function migrationsUninstall(): void
	{
		Schema::disableForeignKeyConstraints();
		
		Schema::dropIfExists('domains');
		Schema::dropIfExists('domain_settings');
		Schema::dropIfExists('domain_meta_tags');
		Schema::dropIfExists('domain_sections');
		
		Schema::enableForeignKeyConstraints();
	}
}
