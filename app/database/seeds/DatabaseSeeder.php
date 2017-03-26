<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
    {
        Eloquent::unguard();

        $this->call('RolTableSeeder');
				$this->call('UserTableSeeder');
        $this->call('TurnoTableSeeder');
        $this->call('OperadorTableSeeder');
        $this->call('RansaUserTableSeeder');

        $this->command->info('All tables seeded!');
	}

}
