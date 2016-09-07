<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationLaravelDealerModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasTable('dealer_categories')) {
            Schema::create('dealer_categories', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('parent_id')->nullable();
                $table->integer('lft')->nullable();
                $table->integer('rgt')->nullable();
                $table->integer('depth')->nullable();

                // kategoriye bağlı görünürlük ayarları
                $table->boolean('show_address')->default(1);
                $table->boolean('show_province')->default(1);
                $table->boolean('show_county')->default(1);
                $table->boolean('show_district')->default(1);
                $table->boolean('show_neighborhood')->default(1);
                $table->boolean('show_postal_code')->default(1);
                $table->boolean('show_land_phone')->default(1);
                $table->boolean('show_mobile_phone')->default(1);
                $table->boolean('show_url')->default(1);

                $table->string('name');
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('dealers')) {
            Schema::create('dealers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned()->nullable();
                $table->foreign('category_id')->references('id')->on('dealer_categories')->onDelete('cascade');

                $table->string('name');
                $table->string('address')->nullable();

                $table->integer('province_id')->unsigned();
                $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
                $table->integer('county_id')->unsigned();
                $table->foreign('county_id')->references('id')->on('counties')->onDelete('cascade');
                $table->integer('district_id')->unsigned()->nullable();
                $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
                $table->integer('neighborhood_id')->unsigned()->nullable();
                $table->foreign('neighborhood_id')->references('id')->on('neighborhoods')->onDelete('cascade');
                $table->integer('postal_code_id')->unsigned()->nullable();
                $table->foreign('postal_code_id')->references('id')->on('postal_codes')->onDelete('cascade');

                $table->char('land_phone', 16)->nullable(); // ETC: 0(216) 333 33 33
                $table->char('mobile_phone', 16)->nullable(); // ETC: 0(506) 333 33 33
                $table->string('url')->nullable();

                $table->boolean('is_publish')->default(0);
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dealers');
        Schema::drop('dealer_categories');
    }
}
