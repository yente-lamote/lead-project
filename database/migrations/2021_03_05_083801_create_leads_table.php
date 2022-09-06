<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            //company die initieel lead heeft aangemaakt
            //de tussentabel is dan voor alle companies die de lead mogen zien
            $table->unsignedBigInteger('company_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('planned_date');
            $table->string('domain_name')->default('')->nullable();
            $table->string('path')->default('')->nullable();
            $table->string('client_ip_address')->default('')->nullable();
            $table->string('user_agent_string')->default('')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
