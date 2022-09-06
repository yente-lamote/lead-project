<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email'=>$this->faker->unique()->safeEmail,
            'first_name'=>$this->faker->firstName,
            'last_name'=>$this->faker->lastName,
            'planned_date'=>$this->faker->dateTimeBetween(now() ,'+2 years'),
            'domain_name'=>$this->faker->domainName(),
            'path'=>'/'.str_replace("-", "/", $this->faker->slug(2)),
            'client_ip_address'=>$this->faker->ipv4(),
            'user_agent_string'=>$this->faker->userAgent(),
            'created_at' => $this->faker->dateTimeBetween('-7 days',now())->format('Y-m-d H:i:s'),
            'status_id' => Status::inRandomOrder()->first()->id,

        ];
    }
}
