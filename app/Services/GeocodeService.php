<?php

namespace App\Services;

use CodeIgniter\HTTP\CURLRequest;

class GeocodeService
{
    private $nominatimUrl = 'https://nominatim.openstreetmap.org';
    private $client;

    public function __construct()
    {
        $this->client = \Config\Services::curlrequest();
    }

    /**
     * Get coordinates from address using Nominatim API
     * @param string $address Address to geocode
     * @return array|false Coordinates and address or false if not found
     */
    public function getCoordinates(string $address)
    {
        try {
            $response = $this->client->get($this->nominatimUrl . '/search', [
                'query' => [
                    'q' => $address,
                    'format' => 'json',
                    'limit' => 1,
                ],
                'headers' => [
                    'User-Agent' => 'JajanMap/1.0',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (!empty($data) && isset($data[0])) {
                $location = $data[0];
                return [
                    'latitude' => (float)$location['lat'],
                    'longitude' => (float)$location['lon'],
                    'address' => $location['display_name'],
                    'geohash' => $this->generateGeohash((float)$location['lat'], (float)$location['lon']),
                ];
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'Geocoding error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reverse geocoding - Get address from coordinates
     * @param float $latitude
     * @param float $longitude
     * @return array|false Address or false if not found
     */
    public function getReverseCoordinates(float $latitude, float $longitude)
    {
        try {
            $response = $this->client->get($this->nominatimUrl . '/reverse', [
                'query' => [
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'format' => 'json',
                ],
                'headers' => [
                    'User-Agent' => 'JajanMap/1.0',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['display_name'])) {
                return [
                    'latitude' => (float)$data['lat'],
                    'longitude' => (float)$data['lon'],
                    'address' => $data['display_name'],
                    'geohash' => $this->generateGeohash((float)$data['lat'], (float)$data['lon']),
                ];
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'Reverse geocoding error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate geohash from latitude and longitude
     * Simplified geohash generation
     */
    private function generateGeohash(float $latitude, float $longitude, int $precision = 12): string
    {
        $lat_min = -90;
        $lat_max = 90;
        $lon_min = -180;
        $lon_max = 180;

        $geohash = '';
        $bits = 0;
        $bit = 0;
        $ch = 0;

        $even = true;

        while (strlen($geohash) < $precision) {
            if ($even) {
                $mid = ($lon_min + $lon_max) / 2;
                if ($longitude > $mid) {
                    $ch |= (1 << (4 - $bit));
                    $lon_min = $mid;
                } else {
                    $lon_max = $mid;
                }
            } else {
                $mid = ($lat_min + $lat_max) / 2;
                if ($latitude > $mid) {
                    $ch |= (1 << (4 - $bit));
                    $lat_min = $mid;
                } else {
                    $lat_max = $mid;
                }
            }

            $even = !$even;

            if ($bit < 4) {
                $bit++;
            } else {
                $geohash .= self::base32Encode($ch);
                $bit = 0;
                $ch = 0;
            }
        }

        return $geohash;
    }

    /**
     * Base32 encoding for geohash
     */
    private static function base32Encode(int $value): string
    {
        $base32 = '0123456789bcdefghjkmnpqrstuvwxyz';
        return $base32[$value & 31];
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float Distance in kilometers
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c;
    }
}
