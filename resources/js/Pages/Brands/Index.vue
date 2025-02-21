<template>
    <app-layout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Manage Brands
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- Action Buttons -->
            <div class="flex justify-end mb-6">
              <Link
                :href="route('brands.create')"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
              >
                Add New Brand
              </Link>
            </div>
            
            <!-- Brands Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Description
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Products
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="brand in brands.data" :key="brand.id">
                    <td class="px-6 py-4 whitespace-nowrap font-medium">
                      {{ brand.name }}
                    </td>
                    <td class="px-6 py-4">
                      <p class="line-clamp-2 text-sm text-gray-600">
                        {{ brand.description || 'No description' }}
                      </p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      {{ brand.products_count }} products
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                      <Link
                        :href="route('brands.edit', brand.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        Edit
                      </Link>
                      
                      <button
                        @click="confirmBrandDeletion(brand)"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <!-- Pagination -->
            <pagination :links="brands.links" class="mt-6" />
            
            <!-- Delete Confirmation Modal -->
            <jet-confirmation-modal :show="confirmingBrandDeletion" @close="closeModal">
              <template #title>
                Delete Brand
              </template>
              
              <template #content>
                <p>Are you sure you want to delete this brand?</p>
                <p class="mt-2 text-sm text-red-600">
                  <strong>Warning:</strong> This will also delete all products associated with this brand.
                </p>
              </template>
              
              <template #footer>
                <jet-secondary-button @click="closeModal">
                  Cancel
                </jet-secondary-button>
                
                <jet-danger-button
                  class="ml-3"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                  @click="deleteBrand"
                >
                  Delete
                </jet-danger-button>
              </template>
            </jet-confirmation-modal>
          </div>
        </div>
      </div>
    </app-layout>
  </template>
  
  <script>
  import { ref } from 'vue';
  import { Link, useForm } from '@inertiajs/inertia-vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue';
  import JetDangerButton from '@/Jetstream/DangerButton.vue';
  import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
  import Pagination from '@/Components/Pagination.vue';
  
  export default {
    components: {
      AppLayout,
      Link,
      JetConfirmationModal,
      JetDangerButton,
      JetSecondaryButton,
      Pagination,
    },
    props: {
      brands: Object,
    },
    setup() {
      const confirmingBrandDeletion = ref(false);
      const brandBeingDeleted = ref(null);
      
      const form = useForm({});
      
      const confirmBrandDeletion = (brand) => {
        brandBeingDeleted.value = brand;
        confirmingBrandDeletion.value = true;
      };
      
      const closeModal = () => {
        confirmingBrandDeletion.value = false;
        setTimeout(() => {
          brandBeingDeleted.value = null;
        }, 300);
      };
      
      const deleteBrand = () => {
        form.delete(route('brands.destroy', brandBeingDeleted.value.id), {
          preserveScroll: true,
          onSuccess: () => closeModal(),
        });
      };
      
      return {
        confirmingBrandDeletion,
        form,
        confirmBrandDeletion,
        closeModal,
        deleteBrand,
      };
    },
  };
  </script>