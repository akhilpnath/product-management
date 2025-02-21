<template>
    <app-layout>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 text-center">
            <div class="mb-6">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Payment Failed</h1>
            <p class="text-gray-600 mb-8">We couldn't process your payment. Please try again or use a different payment method.</p>
            
            <div class="flex flex-col md:flex-row justify-center gap-4">
              <button
                @click="$inertia.back()"
                class="bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700 transition"
              >
                Try Again
              </button>
              
              <Link
                :href="route('home')"
                class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-semibold hover:bg-gray-300 transition"
              >
                Return to Home
              </Link>
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
  };
  </script>