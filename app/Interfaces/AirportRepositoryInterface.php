<?php

namespace App\Interfaces;

interface AirportRepositoryInterface
{
    public function getAllAirports();

    public function getAllAirportBySlug($slug);

    public function getAllAirportByIataCode($iataCode);
}