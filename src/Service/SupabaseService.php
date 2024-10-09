<?php

// src/Service/SupabaseService.php
namespace App\Service;

use Supabase\Client;
use Supabase\SupabaseClient;

class SupabaseService
{
    private SupabaseClient $client;

    public function __construct(string $supabaseUrl, string $supabaseKey)
    {
        $this->client = new Client($supabaseUrl, $supabaseKey);
    }

    public function getClient(): SupabaseClient
    {
        return $this->client;
    }
}

