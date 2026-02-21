<?php

namespace Tests\Feature;

use App\Services\DuffelService;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DuffelV2Test extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $duffelService;
    protected $pricingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->duffelService = app(DuffelService::class);
        $this->pricingService = app(PricingService::class);
    }

    /**
     * Test Duffel API v2 connection
     */
    public function test_duffel_api_connection()
    {
        $connected = $this->duffelService->testConnection();

        // Should return true in mock mode or if API key is configured
        $this->assertIsBool($connected);
    }

    /**
     * Test flight search with v2 API
     */
    public function test_flight_search_v2()
    {
        $result = $this->duffelService->searchFlightsV2(
            'CDG', // Paris
            'JFK', // New York
            '2024-12-01',
            null,
            [
                'adults' => 1,
                'cabin_class' => 'economy',
                'conditions_requested' => [
                    'change_before_departure' => ['allowed' => true],
                    'refund_before_departure' => ['allowed' => true],
                ]
            ]
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertArrayHasKey('flights', $result);

        if (!$result['error']) {
            $this->assertIsArray($result['flights']);
            $this->assertArrayHasKey('search_info', $result);
        }
    }

    /**
     * Test pricing service commission calculation
     */
    public function test_pricing_commission_calculation()
    {
        $basePrice = 50000; // XOF

        $pricing = $this->pricingService->calculatePriceWithCommissionV2(
            $basePrice,
            'XOF',
            'economy'
        );

        $this->assertIsArray($pricing);
        $this->assertArrayHasKey('base_price', $pricing);
        $this->assertArrayHasKey('commission_amount', $pricing);
        $this->assertArrayHasKey('total_price', $pricing);
        $this->assertArrayHasKey('commission_percentage', $pricing);

        // Economy commission should be 15%
        $this->assertEquals(15, $pricing['commission_percentage']);
        $this->assertEquals(7500, $pricing['commission_amount']); // 15% of 50000
        $this->assertEquals(57500, $pricing['total_price']); // 50000 + 7500
    }

    /**
     * Test business class commission
     */
    public function test_business_class_commission()
    {
        $basePrice = 100000; // XOF

        $pricing = $this->pricingService->calculatePriceWithCommissionV2(
            $basePrice,
            'XOF',
            'business'
        );

        // Business commission should be 12%
        $this->assertEquals(12, $pricing['commission_percentage']);
        $this->assertEquals(12000, $pricing['commission_amount']); // 12% of 100000
        $this->assertEquals(112000, $pricing['total_price']); // 100000 + 12000
    }

    /**
     * Test first class commission
     */
    public function test_first_class_commission()
    {
        $basePrice = 200000; // XOF

        $pricing = $this->pricingService->calculatePriceWithCommissionV2(
            $basePrice,
            'XOF',
            'first'
        );

        // First class commission should be 10%
        $this->assertEquals(10, $pricing['commission_percentage']);
        $this->assertEquals(20000, $pricing['commission_amount']); // 10% of 200000
        $this->assertEquals(220000, $pricing['total_price']); // 200000 + 20000
    }

    /**
     * Test refund amount calculation
     */
    public function test_refund_calculation()
    {
        $originalPrice = 100000; // XOF
        $conditions = [
            'refund_before_departure' => [
                'allowed' => true,
                'penalty_amount' => 10000,
                'penalty_currency' => 'XOF'
            ]
        ];

        $refund = $this->pricingService->calculateRefundAmount(
            $originalPrice,
            $conditions,
            'XOF'
        );

        $this->assertIsArray($refund);
        $this->assertArrayHasKey('refund_allowed', $refund);
        $this->assertArrayHasKey('refund_amount', $refund);
        $this->assertArrayHasKey('penalty_amount', $refund);

        $this->assertTrue($refund['refund_allowed']);
        $this->assertEquals(90000, $refund['refund_amount']); // 100000 - 10000
        $this->assertEquals(10000, $refund['penalty_amount']);
    }

    /**
     * Test change fee calculation
     */
    public function test_change_fee_calculation()
    {
        $originalPrice = 100000; // XOF
        $conditions = [
            'change_before_departure' => [
                'allowed' => true,
                'penalty_amount' => 15000,
                'penalty_currency' => 'XOF'
            ]
        ];

        $changeFee = $this->pricingService->calculateChangeFee(
            $originalPrice,
            $conditions,
            'XOF'
        );

        $this->assertIsArray($changeFee);
        $this->assertArrayHasKey('change_allowed', $changeFee);
        $this->assertArrayHasKey('change_fee', $changeFee);

        $this->assertTrue($changeFee['change_allowed']);
        $this->assertEquals(15000, $changeFee['change_fee']);
    }

    /**
     * Test currency conversion
     */
    public function test_currency_conversion()
    {
        $eurAmount = 100;
        $convertedAmount = $this->pricingService->convertToXof($eurAmount, 'EUR');

        // Should convert EUR to XOF using the configured rate
        $expectedAmount = $eurAmount * 655.957; // Current rate
        $this->assertEquals(round($expectedAmount), $convertedAmount);
    }

    /**
     * Test airport search functionality
     */
    public function test_airport_search()
    {
        $airports = $this->duffelService->searchAirportsV2('Paris');

        $this->assertIsArray($airports);

        // Should find Paris airports
        $parisAirports = array_filter($airports, function($airport) {
            return str_contains(strtolower($airport['city'] ?? ''), 'paris');
        });

        $this->assertNotEmpty($parisAirports);
    }

    /**
     * Test flight search routes
     */
    public function test_flight_search_routes()
    {
        // Test basic search route
        $response = $this->post('/flights/search', [
            'departure_id' => 'CDG',
            'arrival_id' => 'JFK',
            'outbound_date' => '2024-12-01',
            'adults' => 1,
            'type' => 2
        ]);

        $response->assertStatus(200);

        // Test advanced search route
        $response = $this->post('/flights/search-advanced', [
            'departure_id' => 'CDG',
            'arrival_id' => 'JFK',
            'outbound_date' => '2024-12-01',
            'adults' => 1,
            'travel_class' => 'economy'
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test flight details route
     */
    public function test_flight_details_route()
    {
        // Test with mock offer ID
        $response = $this->get('/flights/mock_offer_123/details');

        // Should return 200 even if offer not found (shows error page)
        $response->assertStatus(200);
    }

    /**
     * Test webhook signature verification
     */
    public function test_webhook_signature_verification()
    {
        $payload = [
            'type' => 'order.confirmed',
            'data' => ['id' => 'test_order']
        ];

        $signature = 'test_signature';

        // Should return false with invalid signature
        $isValid = $this->duffelService->verifyWebhookSignature($payload, $signature);
        $this->assertFalse($isValid);
    }

    /**
     * Test commission rates configuration
     */
    public function test_commission_rates_configuration()
    {
        $rates = $this->pricingService->getAllCommissionRates();

        $this->assertIsArray($rates);
        $this->assertArrayHasKey('economy', $rates);
        $this->assertArrayHasKey('business', $rates);
        $this->assertArrayHasKey('first', $rates);

        $this->assertEquals(15, $rates['economy']);
        $this->assertEquals(12, $rates['business']);
        $this->assertEquals(10, $rates['first']);
    }

    /**
     * Test mock flight data generation
     */
    public function test_mock_flight_generation()
    {
        $result = $this->duffelService->searchFlightsV2(
            'ABJ',
            'CDG',
            '2024-12-01',
            null,
            ['adults' => 1]
        );

        // In mock mode, should return mock data
        if (config('services.duffel.use_mock', true)) {
            $this->assertIsArray($result);
            $this->assertArrayHasKey('flights', $result);
            $this->assertArrayHasKey('search_info', $result);
            $this->assertTrue($result['search_info']['mock_data'] ?? false);
        }
    }
}
