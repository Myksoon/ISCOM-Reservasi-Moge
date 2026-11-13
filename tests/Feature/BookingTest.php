<?php

namespace Tests\Feature;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_fleet(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ducati Panigale V2');
        $response->assertSee('Booking sekarang');
    }

    public function test_index_displays_bookings(): void
    {
        Booking::create([
            'nama' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'tanggal' => Carbon::now()->addDays(2)->toDateString(),
            'jam' => '10:00:00',
            'tanggal_selesai' => Carbon::now()->addDays(2)->toDateString(),
            'jam_selesai' => '18:00:00',
            'catatan' => 'Kawasaki ZX-6R',
            'status' => 'menunggu',
        ]);

        $response = $this->get(route('bookings.index'));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('081234567890');
        $response->assertSee('Menunggu');
    }

    public function test_create_page_loads_successfully(): void
    {
        $response = $this->get(route('bookings.create'));
        $response->assertStatus(200);
        $response->assertSee('Booking baru');
    }

    public function test_store_creates_booking_and_redirects(): void
    {
        $data = [
            'nama' => 'Ahmad Dani',
            'no_hp' => '081987654321',
            'moge' => 'Ducati Panigale V2',
            'tanggal' => Carbon::now()->addDays(5)->toDateString(),
            'jam' => '14:30',
            'tanggal_selesai' => Carbon::now()->addDays(5)->toDateString(),
            'jam_selesai' => '20:30',
            'catatan' => 'Harley Iron 883 Surabaya Barat',
        ];

        $response = $this->post(route('bookings.store'), $data);

        $response->assertRedirect(route('bookings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'nama' => 'Ahmad Dani',
            'no_hp' => '081987654321',
            'status' => 'menunggu',
        ]);
    }

    public function test_store_marks_as_disewa_when_ongoing(): void
    {
        $data = [
            'nama' => 'Satriya Racing',
            'no_hp' => '0812341234',
            'moge' => 'Ducati Panigale V2',
            'tanggal' => Carbon::now()->toDateString(),
            'jam' => Carbon::now()->subHour()->format('H:i'),
            'tanggal_selesai' => Carbon::now()->toDateString(),
            'jam_selesai' => Carbon::now()->addHours(2)->format('H:i'),
            'catatan' => 'Yamaha R1M',
        ];

        $response = $this->post(route('bookings.store'), $data);

        $response->assertRedirect(route('bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'nama' => 'Satriya Racing',
            'status' => 'disewa',
        ]);
    }

    public function test_store_marks_as_selesai_if_return_datetime_is_past(): void
    {
        $data = [
            'nama' => 'Gerry Salim',
            'no_hp' => '0812341234',
            'moge' => 'Ducati Panigale V2',
            'tanggal' => Carbon::now()->subDays(2)->toDateString(),
            'jam' => '10:00',
            'tanggal_selesai' => Carbon::now()->subDays(1)->toDateString(),
            'jam_selesai' => '17:00',
            'catatan' => 'Honda CBR1000RR',
        ];

        $response = $this->post(route('bookings.store'), $data);

        $response->assertRedirect(route('bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'nama' => 'Gerry Salim',
            'status' => 'selesai',
        ]);
    }

    public function test_index_automatically_updates_status_to_disewa_and_selesai(): void
    {
        $activeBooking = Booking::create([
            'nama' => 'Dimas Active',
            'no_hp' => '08129876543',
            'tanggal' => Carbon::now()->toDateString(),
            'jam' => Carbon::now()->subHour()->format('H:i:s'),
            'tanggal_selesai' => Carbon::now()->toDateString(),
            'jam_selesai' => Carbon::now()->addHours(3)->format('H:i:s'),
            'catatan' => 'Honda CBR600RR',
            'status' => 'menunggu',
        ]);

        $finishedBooking = Booking::create([
            'nama' => 'Dimas Finished',
            'no_hp' => '08129876544',
            'tanggal' => Carbon::now()->subDays(2)->toDateString(),
            'jam' => '08:00:00',
            'tanggal_selesai' => Carbon::now()->subDays(1)->toDateString(),
            'jam_selesai' => '17:00:00',
            'catatan' => 'Ducati Monster',
            'status' => 'disewa',
        ]);

        $response = $this->get(route('bookings.index'));
        $response->assertStatus(200);

        $this->assertEquals('disewa', $activeBooking->fresh()->status);
        $this->assertEquals('selesai', $finishedBooking->fresh()->status);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->post(route('bookings.store'), []);

        $response->assertSessionHasErrors(['nama', 'no_hp', 'tanggal', 'jam', 'tanggal_selesai', 'jam_selesai']);
    }

    public function test_edit_page_loads_with_booking_data(): void
    {
        $booking = Booking::create([
            'nama' => 'Citra Kirana',
            'no_hp' => '08551234567',
            'tanggal' => Carbon::now()->addDays(3)->toDateString(),
            'jam' => '09:00:00',
            'tanggal_selesai' => Carbon::now()->addDays(3)->toDateString(),
            'jam_selesai' => '17:00:00',
            'catatan' => 'Ducati Panigale V2',
            'status' => 'menunggu',
        ]);

        $response = $this->get(route('bookings.edit', $booking));
        $response->assertStatus(200);
        $response->assertSee('Citra Kirana');
        $response->assertSee('08551234567');
    }

    public function test_update_modifies_booking_and_status(): void
    {
        $booking = Booking::create([
            'nama' => 'Citra Kirana',
            'no_hp' => '08551234567',
            'tanggal' => Carbon::now()->addDays(3)->toDateString(),
            'jam' => '09:00:00',
            'tanggal_selesai' => Carbon::now()->addDays(3)->toDateString(),
            'jam_selesai' => '17:00:00',
            'catatan' => 'Ducati Panigale V2',
            'status' => 'menunggu',
        ]);

        $updateData = [
            'nama' => 'Citra Kirana Updated',
            'no_hp' => '08551234567',
            'moge' => 'Ducati Panigale V2',
            'tanggal' => Carbon::now()->addDays(3)->toDateString(),
            'jam' => '11:00',
            'tanggal_selesai' => Carbon::now()->addDays(3)->toDateString(),
            'jam_selesai' => '19:00',
            'catatan' => 'Ducati Panigale V2 + Full Gear',
            'status' => 'disewa',
        ];

        $response = $this->put(route('bookings.update', $booking), $updateData);

        $response->assertRedirect(route('bookings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'nama' => 'Citra Kirana Updated',
            'status' => 'disewa',
        ]);
    }

    public function test_destroy_deletes_booking(): void
    {
        $booking = Booking::create([
            'nama' => 'Eko Prasetyo',
            'no_hp' => '08123444555',
            'tanggal' => Carbon::now()->addDays(4)->toDateString(),
            'jam' => '16:00:00',
            'tanggal_selesai' => Carbon::now()->addDays(4)->toDateString(),
            'jam_selesai' => '20:00:00',
            'catatan' => 'Honda CBR1000RR-R',
            'status' => 'menunggu',
        ]);

        $response = $this->delete(route('bookings.destroy', $booking));

        $response->assertRedirect(route('bookings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    public function test_update_status_to_delay_and_reschedule(): void
    {
        $booking = Booking::create([
            'nama' => 'Rider Delay',
            'no_hp' => '08123444555',
            'tanggal' => Carbon::now()->subDays(1)->toDateString(),
            'jam' => '10:00:00',
            'tanggal_selesai' => Carbon::now()->toDateString(),
            'jam_selesai' => '12:00:00',
            'catatan' => 'Ninja H2',
            'status' => 'disewa',
        ]);

        $updateDelay = [
            'nama' => 'Rider Delay',
            'no_hp' => '08123444555',
            'moge' => 'Ducati Panigale V2',
            'tanggal' => Carbon::now()->subDays(1)->toDateString(),
            'jam' => '10:00',
            'tanggal_selesai' => Carbon::now()->toDateString(),
            'jam_selesai' => '12:00',
            'catatan' => 'Ninja H2',
            'status' => 'delay',
        ];

        $this->put(route('bookings.update', $booking), $updateDelay)
            ->assertRedirect(route('bookings.index'));

        $this->assertEquals('delay', $booking->fresh()->status);

        $updateReschedule = array_merge($updateDelay, ['status' => 'reschedule']);
        $this->put(route('bookings.update', $booking), $updateReschedule)
            ->assertRedirect(route('bookings.index'));

        $this->assertEquals('reschedule', $booking->fresh()->status);

        $response = $this->get(route('bookings.index'));
        $response->assertSee('Reschedule');
    }

    public function test_store_rejects_overlapping_schedule_for_same_bike(): void
    {
        $data = [
            'nama' => 'Penyewa Pertama',
            'no_hp' => '081200000001',
            'moge' => 'Yamaha YZF-R1M',
            'tanggal' => Carbon::now()->addDays(7)->toDateString(),
            'jam' => '09:00',
            'tanggal_selesai' => Carbon::now()->addDays(8)->toDateString(),
            'jam_selesai' => '09:00',
        ];
        $this->post(route('bookings.store'), $data)->assertSessionHasNoErrors();

        $bentrok = array_merge($data, [
            'nama' => 'Penyewa Kedua',
            'tanggal' => Carbon::now()->addDays(7)->toDateString(),
            'jam' => '15:00',
            'tanggal_selesai' => Carbon::now()->addDays(9)->toDateString(),
        ]);
        $this->post(route('bookings.store'), $bentrok)->assertSessionHasErrors('moge');
        $this->assertDatabaseMissing('bookings', ['nama' => 'Penyewa Kedua']);

        // motor lain di jam yang sama tetap boleh
        $this->post(route('bookings.store'), array_merge($bentrok, ['moge' => 'Honda CBR1000RR-R']))
            ->assertSessionHasNoErrors();

        // mulai tepat saat motor dikembalikan juga boleh
        $sesudah = array_merge($bentrok, [
            'nama' => 'Penyewa Ketiga',
            'tanggal' => Carbon::now()->addDays(8)->toDateString(),
            'jam' => '09:00',
        ]);
        $this->post(route('bookings.store'), $sesudah)->assertSessionHasNoErrors();
    }

    public function test_store_rejects_return_time_before_start(): void
    {
        $response = $this->post(route('bookings.store'), [
            'nama' => 'Salah Jam',
            'no_hp' => '081200000002',
            'moge' => 'Kawasaki Ninja ZX-6R',
            'tanggal' => Carbon::now()->addDays(3)->toDateString(),
            'jam' => '14:00',
            'tanggal_selesai' => Carbon::now()->addDays(3)->toDateString(),
            'jam_selesai' => '10:00',
        ]);

        $response->assertSessionHasErrors('jam_selesai');
    }

    public function test_index_can_search_and_filter_by_status(): void
    {
        $dasar = [
            'no_hp' => '081200000003',
            'tanggal' => Carbon::now()->addDays(2)->toDateString(),
            'jam' => '10:00:00',
            'tanggal_selesai' => Carbon::now()->addDays(2)->toDateString(),
            'jam_selesai' => '18:00:00',
        ];
        Booking::create($dasar + ['nama' => 'Rina Wijaya', 'status' => 'menunggu']);
        Booking::create($dasar + ['nama' => 'Joko Susilo', 'status' => 'batal']);

        $this->get(route('bookings.index', ['q' => 'Rina']))
            ->assertSee('Rina Wijaya')
            ->assertDontSee('Joko Susilo');

        $this->get(route('bookings.index', ['status' => 'batal']))
            ->assertSee('Joko Susilo')
            ->assertDontSee('Rina Wijaya');
    }
}
