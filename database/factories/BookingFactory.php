<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Facilitie;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::all();
        $facilities = Facilitie::all();
        $members = Member::all();

        return [
            'facid' => $facilities->random()->facid,
            'memid' => $members->random()->memid,
            'starttime' => $this->faker->dateTimeBetween('2012-07-03', '2013-01-01'),
            'slots' => $this->faker->numberBetween(1, 14),
            'createdby' => $users->random()->userid
        ];
    }
}
