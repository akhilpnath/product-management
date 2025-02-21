<template>
    <app-layout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Products
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Featured Products</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <div v-for="product in products" :key="product.id" class="border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                  <img 
                    v-if="product.image_path" 
                    :src="`/storage/${product.image_path}`" 
                    :alt="product.name"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="text-gray-400">No image</div>
                </div>
                
                <div class="p-4">
                  <h3 class="font-semibold text-lg">{{ product.name }}</h3>
                  <p class="text-gray-600 text-sm">{{ product.brand.name }}</p>
                  <p class="font-bold text-lg mt-2">${{ product.price }}</p>
                  
                  <Link 
                    :href="route('products.show', product.id)" 
                    class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                  >
                    View Details
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </app-layout>
  </template>
  
  <script>
  import { Link } from '@inertiajs/inertia-vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  export default {
    components: {
      AppLayout,
      Link,
    },
    props: {
      products: Array,
    },
  };
  </script>


