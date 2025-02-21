<template>
    <app-layout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Import Products
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="mb-6">
              <p class="text-gray-700">
                Upload an Excel file (.xlsx or .xls) with your product data. The file should have the following columns:
              </p>
              <ul class="list-disc ml-6 mt-2 text-gray-600">
                <li>name (required): Product name</li>
                <li>description (optional): Product description</li>
                <li>price (required): Product price (numeric)</li>
                <li>brand_id (required): ID of an existing brand</li>
              </ul>
            </div>
            
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
              <h3 class="text-blue-800 font-semibold">Download Template</h3>
              <p class="text-blue-600 mt-1">
                You can download a sample Excel template to see the required format.
              </p>
              <a 
                href="#" 
                class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
              >
                Download Template
              </a>
            </div>
            
            <form @submit.prevent="submit" class="mt-8 border-t pt-6">
              <div>
                <jet-label for="file" value="Excel File" />
                <input
                  id="file"
                  type="file"
                  class="mt-1 block w-full"
                  accept=".xlsx, .xls"
                  @input="form.file = $event.target.files[0]"
                  required
                />
                <jet-input-error :message="form.errors.file" class="mt-2" />
              </div>
              
              <div class="flex items-center justify-end mt-8">
                <Link
                  :href="route('products.index')"
                  class="text-gray-600 hover:text-gray-900 mr-4"
                >
                  Cancel
                </Link>
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  Import Products
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
  import JetInputError from '@/Jetstream/InputError.vue';
  import JetLabel from '@/Jetstream/Label.vue';
  
  export default {
    components: {
      AppLayout,
      JetButton,
      JetInputError,
      JetLabel,
      Link,
    },
    setup() {
      const form = useForm({
        file: null,
      });
  
      const submit = () => {
        form.post(route('products.import'), {
          preserveScroll: true,
        });
      };
  
      return { form, submit };
    },
  };
  </script>