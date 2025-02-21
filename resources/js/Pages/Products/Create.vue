<template>
    <app-layout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Create Product
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <jet-label for="name" value="Product Name" />
                  <jet-input
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                  />
                  <jet-input-error :message="form.errors.name" class="mt-2" />
                </div>
                <div>
                  <jet-label for="price" value="Price ($)" />
                  <jet-input
                    id="price"
                    type="number"
                    step="0.01"
                    min="0"
                    class="mt-1 block w-full"
                    v-model="form.price"
                    required
                  />
                  <jet-input-error :message="form.errors.price" class="mt-2" />
                </div>
                
                <div>
                  <jet-label for="brand_id" value="Brand" />
                  <select
                    id="brand_id"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    v-model="form.brand_id"
                    required
                  >
                    <option value="" disabled>Select a brand</option>
                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                      {{ brand.name }}
                    </option>
                  </select>
                  <jet-input-error :message="form.errors.brand_id" class="mt-2" />
                </div>
                
                <div>
                  <jet-label for="image" value="Product Image" />
                  <input
                    id="image"
                    type="file"
                    class="mt-1 block w-full"
                    @input="form.image = $event.target.files[0]"
                  />
                  <jet-input-error :message="form.errors.image" class="mt-2" />
                </div>
              </div>
              
              <div class="mt-6">
                <jet-label for="description" value="Description" />
                <textarea
                  id="description"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                  rows="6"
                  v-model="form.description"
                ></textarea>
                <jet-input-error :message="form.errors.description" class="mt-2" />
              </div>
              
              <div class="flex items-center justify-end mt-8">
                <Link
                  :href="route('products.index')"
                  class="text-gray-600 hover:text-gray-900 mr-4"
                >
                  Cancel
                </Link>
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  Create Product
                </jet-button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </app-layout>
  </template>
  
  <script>
  import { useForm, Link } from '@inertiajs/inertia-vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import JetButton from '@/Jetstream/Button.vue';
  import JetInput from '@/Jetstream/Input.vue';
  import JetInputError from '@/Jetstream/InputError.vue';
  import JetLabel from '@/Jetstream/Label.vue';
  
  export default {
    components: {
      AppLayout,
      JetButton,
      JetInput,
      JetInputError,
      JetLabel,
      Link,
    },
    props: {
      brands: Array,
    },
    setup() {
      const form = useForm({
        name: '',
        description: '',
        price: '',
        brand_id: '',
        image: null,
      });
  
      const submit = () => {
        form.post(route('products.store'), {
          preserveScroll: true,
        });
      };
  
      return { form, submit };
    },
  };
  </script>