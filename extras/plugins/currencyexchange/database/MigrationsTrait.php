<?php

namespace extras\plugins\currencyexchange\database;

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
		
		// Add the 'currencies' column in the 'countries' table
		if (!Schema::hasColumn('countries', 'currencies')) {
			Schema::table('countries', function (Blueprint $table) {
				$table->text('currencies')
					->nullable()
					->after('equivalent_fips_code');
			});
		}
		
		// Add the 'rate' column in the 'currencies' table
		if (!Schema::hasColumn('currencies', 'rate')) {
			Schema::table('currencies', function (Blueprint $table) {
				$table->float('rate')
					->nullable()
					->comment('Rate related to the currency conversion base')
					->after('html_entities');
			});
		}
		
		Schema::enableForeignKeyConstraints();
	}
	
	/**
	 * @return void
	 */
	private static function migrationsUninstall(): void
	{
		Schema::disableForeignKeyConstraints();
		
		// Drop the 'currencies' column from the 'countries' table
		if (Schema::hasColumn('countries', 'currencies')) {
			Schema::table('countries', function (Blueprint $table) {
				$table->dropColumn('currencies');
			});
		}
		
		// Drop the 'rate' column from the 'currencies' table
		if (Schema::hasColumn('currencies', 'rate')) {
			Schema::table('currencies', function (Blueprint $table) {
				$table->dropColumn('rate');
			});
		}
		
		Schema::enableForeignKeyConstraints();
	}
}
