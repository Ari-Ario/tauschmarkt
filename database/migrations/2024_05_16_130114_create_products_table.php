<?php
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('published')->default(0);
            $table->boolean('inStock')->default(0);
            $table->decimal('amount', 10, 2);
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->string('product_picture')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');

            // $table->foreignId(User::class, 'created_by')->nullable();
            // $table->foreignId(User::class, 'updated_by')->nullable();
            // $table->foreignId(User::class,'deleted_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
