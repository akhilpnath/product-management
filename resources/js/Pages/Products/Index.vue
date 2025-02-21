<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Products
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex flex-wrap gap-4 mb-6">
            <Link :href="route('products.create')"
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            Add New Product
            </Link>

            <Link :href="route('products.import.form')"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Import Products
            </Link>

            <Link :href="route('products.export')"
              class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
            Export Products
            </Link>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Image
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Brand
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Price
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="product in products.data" :key="product.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="h-12 w-12 bg-gray-100 rounded-md overflow-hidden">
                      <img v-if="product.image_path" :src="`/storage/${product.image_path}`" :alt="product.name"
                        class="h-full w-full object-cover" />
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ product.name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ product.brand.name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    ${{ product.price }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <Link :href="route('products.show', product.id)" class="text-blue-600 hover:text-blue-900">
                    View
                    </Link>

                    <Link :href="route('products.edit', product.id)" class="text-indigo-600 hover:text-indigo-900">
                    Edit
                    </Link>

                    <button @click="confirmProductDeletion(product)" class="text-red-600 hover:text-red-900">
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <pagination :links="products.links" class="mt-6" />

          <jet-confirmation-modal :show="confirmingProductDeletion" @close="closeModal">
            <template #title>
              Delete Product
            </template>

            <template #content>
              Are you sure you want to delete this product? This action cannot be undone.
            </template>

            <template #footer>
              <jet-secondary-button @click="closeModal">
                Cancel
              </jet-secondary-button>

              <jet-danger-button class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                @click="deleteProduct">
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
    products: Object,
  },
  setup() {
    const confirmingProductDeletion = ref(false);
    const productBeingDeleted = ref(null);

    const form = useForm({});

    const confirmProductDeletion = (product) => {
      productBeingDeleted.value = product;
      confirmingProductDeletion.value = true;
    };

    const closeModal = () => {
      confirmingProductDeletion.value = false;
      setTimeout(() => {
        productBeingDeleted.value = null;
      }, 300);
    };

    const deleteProduct = () => {
      form.delete(route('products.destroy', productBeingDeleted.value.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
      });
    };

    return {
      confirmingProductDeletion,
      form,
      confirmProductDeletion,
      closeModal,
      deleteProduct,
    };
  },
};
</script>