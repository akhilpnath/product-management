<template>
    <app-layout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Product Details
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="md:flex">
              <div class="md:w-1/2 bg-gray-100 flex items-center justify-center p-6">
                <img 
                  v-if="product.image_path" 
                  :src="`/storage/${product.image_path}`" 
                  :alt="product.name"
                  class="max-h-96 object-contain"
                />
                <div v-else class="text-gray-400 text-xl">No image available</div>
              </div>
              
              <div class="md:w-1/2 p-6">
                <h1 class="text-3xl font-bold">{{ product.name }}</h1>
                <p class="text-gray-600 mt-2">Brand: {{ product.brand.name }}</p>
                
                <div class="text-2xl font-bold text-green-600 mt-4">
                  ${{ product.price }}
                </div>
                
                <div class="mt-6">
                  <h3 class="font-semibold text-lg">Description:</h3>
                  <p class="mt-2 text-gray-700">{{ product.description || 'No description available.' }}</p>
                </div>
                
                <div class="mt-8">
                  <button 
                    @click="initializePayment"
                    class="w-full bg-indigo-600 text-white py-3 px-6 rounded-md font-semibold hover:bg-indigo-700 transition"
                  >
                    Buy Now
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </app-layout>
  </template>
  
  <script>
  import { ref } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import axios from 'axios';
  
  export default {
    components: {
      AppLayout,
    },
    props: {
      product: Object,
      razorpayKey: String,
    },
    setup(props) {
      const isProcessing = ref(false);
  
      const initializePayment = async () => {
        if (isProcessing.value) return;
        isProcessing.value = true;
  
        try {
          const response = await axios.post(route('products.payment', props.product.id));
          const options = {
            key: props.razorpayKey,
            amount: response.data.amount,
            currency: response.data.currency,
            name: "Your Company Name",
            description: `Purchase: ${props.product.name}`,
            order_id: response.data.id,
            handler: function (response) {
              axios.post(route('payment.verify'), {
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature
              }).then(() => {
                Inertia.visit(route('payment.success'));
              }).catch(() => {
                Inertia.visit(route('payment.failed'));
              });
            },
            prefill: {
              name: "",
              email: "",
              contact: ""
            },
            theme: {
              color: "#4F46E5"
            }
          };
  
          const razorpay = new Razorpay(options);
          razorpay.open();
        } catch (error) {
          console.error('Payment initialization failed:', error);
        } finally {
          isProcessing.value = false;
        }
      };
  
      return {
        initializePayment
      };
    }
  };
  </script>