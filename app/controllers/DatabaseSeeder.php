<?php

require_once __DIR__.'/../models/District.php';
require_once __DIR__.'/../models/Cafe.php';
require_once __DIR__.'/../models/Tag.php';

class DatabaseSeeder
{
    public function seed()
    {
        $district = new District;
        $district->seedData();

        $cafe = new Cafe;
        $cafe->seedData();

        $tag = new Tag;
        $tag->seedData();

        echo 'Database seeded successfully!';
    }
}

$seeder = new DatabaseSeeder;
$seeder->seed();
