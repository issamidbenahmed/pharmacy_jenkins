<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Order;
use App\Models\Address;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Doctor;
use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\TestCase;

class CombinedTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_store_creates_new_client()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ];

        $clientData = [
            'gender' => 'male',
            'phone' => '0123456789',
            'date_of_birth' => '2000-01-01',
        ];

        $response = $this->post(route('clients.store'), array_merge($userData, $clientData));

        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
        $this->assertDatabaseHas('clients', ['gender' => $clientData['gender']]);
        $response->assertStatus(302);
    }

    /** @test */
    public function client_update_modifies_client()
    {
        $client = Client::factory()->create();

        $updateData = [
            'phone' => '0987654321',
            'date_of_birth' => '1990-12-31',
        ];

        $response = $this->put(route('clients.update', $client->id), $updateData);

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'phone' => $updateData['phone']]);
        $response->assertStatus(302);
    }

    /** @test */
    public function order_store_creates_new_order()
    {
        $orderData = [
            'client_id' => Client::factory()->create()->id,
            'status' => 'pending',
        ];

        $response = $this->post(route('orders.store'), $orderData);

        $this->assertDatabaseHas('orders', $orderData);
        $response->assertStatus(302);
    }

    /** @test */
    public function address_store_creates_new_address()
    {
        $addressData = [
            'client_id' => Client::factory()->create()->id,
            'area_id' => Area::factory()->create()->id,
            'street_name' => 'Main Street',
            'building_number' => 123,
            'floor_number' => 4,
            'flat_number' => 12,
            'is_main' => true,
        ];

        $response = $this->post(route('addresses.store'), $addressData);

        $this->assertDatabaseHas('addresses', $addressData);
        $response->assertStatus(302);
    }

    /** @test */
    public function pharmacy_store_creates_new_pharmacy()
    {
        $pharmacyData = [
            'name' => 'Health Pharmacy',
            'address' => '123 Health Street',
            'phone' => '0123456789',
        ];

        $response = $this->post(route('pharmacies.store'), $pharmacyData);

        $this->assertDatabaseHas('pharmacies', $pharmacyData);
        $response->assertStatus(302);
    }

    /** @test */
    public function doctor_store_creates_new_doctor()
    {
        $doctorData = [
            'name' => 'Dr. Smith',
            'specialization' => 'Cardiology',
            'phone' => '0123456789',
        ];

        $response = $this->post(route('doctors.store'), $doctorData);

        $this->assertDatabaseHas('doctors', $doctorData);
        $response->assertStatus(302);
    }

    /** @test */
    public function medicine_store_creates_new_medicine()
    {
        $medicineData = [
            'name' => 'Paracetamol',
            'price' => 5.99,
            'stock' => 100,
        ];

        $response = $this->post(route('medicines.store'), $medicineData);

        $this->assertDatabaseHas('medicines', $medicineData);
        $response->assertStatus(302);
    }

    /** @test */
    public function area_store_creates_new_area()
    {
        $areaData = [
            'name' => 'Downtown',
        ];

        $response = $this->post(route('areas.store'), $areaData);

        $this->assertDatabaseHas('areas', $areaData);
        $response->assertStatus(302);
    }

    // Add more tests here for update, delete, and show operations for each entity.
}
