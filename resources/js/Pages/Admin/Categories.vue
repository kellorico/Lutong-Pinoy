<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref } from "vue";

defineProps({
   categories: Array,
});

const form = useForm({
    category_name: null,
});

const showCreateForm = ref(false);
const showEditForm = ref(false);
const selectedCategory = ref(null);

const toggleCreateForm = () => {
    showCreateForm.value = !showCreateForm.value;
};

const editCategory = (category) => {
    selectedCategory.value = { ...category }; // Clone the category data
    form.category_name = selectedCategory.value.category_name; // Pre-fill the form
    showEditForm.value = true; // Show the edit form
};

const submit = () => {
    form.post('/admin/categories/store', {
        onSuccess: () => {
            Swal.fire({
                title: 'Success',
                text: 'Category added successfully.',
                icon: 'success',
                confirmButtonText: 'Close',
            }).then(() => {
                showCreateForm.value = false;
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                title: 'Error',
                text: 'Category failed to add.',
                icon: 'error',
                confirmButtonText: 'Close',
            });
        },
    });
};

const submitEdit = () => {
    form.put(`/admin/categories/update/${selectedCategory.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: 'Success',
                text: 'Category successfully updated.',
                icon: 'success',
                confirmButtonText: 'Close',
            }).then(() => {
                showEditForm.value = false;
                selectedCategory.value = null;
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                title: 'Error',
                text: 'Failed to update Category. Please check the input fields.',
                icon: 'error',
                confirmButtonText: 'Close',
            });
        },
    });
};

const deleteCategory = (category) => {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete the category: ${category.category_name}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(`/admin/categories/delete/${category.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success',
                        text: 'Category successfully deleted.',
                        icon: 'success',
                        confirmButtonText: 'Close'
                    });
                },
                onError: () => {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to delete user. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });
        }
    });
};
</script>

<template>
    <AdminLayout>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <i class="fa-solid fa-tags"></i> Categories
                </li>
            </ol>
        </nav>

        <div class="mt-4">
            <button
                @click="toggleCreateForm"
                class="px-4 py-2 mb-2 bg-[#306679] hover:bg-[#255065] text-white rounded"
            >
                <i class="fa-solid fa-plus"></i> Create Category
            </button>
        </div>

        <!-- Create Form -->
        <div
            v-if="showCreateForm"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
        >
            <div class="bg-white p-4 rounded-md shadow-md w-1/3">
                <h2 class="text-lg font-semibold mb-4">Create Category</h2>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label
                            for="category_name"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Category Name
                        </label>
                        <input
                            type="text"
                            id="category_name"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.category_name"
                            :class="{ 'border-red-500': form.errors.category_name }"
                        />
                        <div v-if="form.errors.category_name" class="text-red-500 text-sm">
                            {{ form.errors.category_name }}
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="button"
                            class="px-4 py-2 bg-gray-500 text-white rounded mr-2"
                            @click="toggleCreateForm"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-[#306679] hover:bg-[#255065] text-white rounded"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Form -->
        <div
            v-if="showEditForm"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
        >
            <div class="bg-white p-4 rounded-md shadow-md w-1/3">
                <h2 class="text-lg font-semibold mb-4">Edit Category</h2>
                <form @submit.prevent="submitEdit">
                    <div class="mb-4">
                        <label
                            for="edit_category_name"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Category Name
                        </label>
                        <input
                            type="text"
                            id="edit_category_name"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.category_name"
                            :class="{ 'border-red-500': form.errors.category_name }"
                        />
                        <div v-if="form.errors.category_name" class="text-red-500 text-sm">
                            {{ form.errors.category_name }}
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="button"
                            class="px-4 py-2 bg-gray-500 text-white rounded mr-2"
                            @click="() => (showEditForm.value = false)"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-[#306679] hover:bg-[#255065] text-white rounded"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <div v-if="categories.length === 0" class="text-center p-4">
                <p class="text-gray-600">No categories found. <span>Create a new category</span>.</p>
            </div>

            <table v-else class="w-full text-sm text-left text-black bg-white">
                <thead class="text-xs text-black uppercase bg-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">#</th>
                    <th scope="col" class="px-6 py-3 text-center">Category Name</th>
                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(category, index) in categories" :key="category.id" class="bg-white border-b">
                    <th class="text-center">{{ index + 1 }}</th>
                    <td class="px-6 py-4 text-center">{{ category.category_name }}</td>
                    <td class="px-6 py-4 text-center">
                        <button
                            class="bg-yellow-500 text-white px-4 py-2 text-sm rounded-md hover:bg-yellow-600 focus:outline-none mx-1"
                            @click="editCategory(category)"
                        >
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button
                            class="bg-red-500 text-white px-4 py-2 text-sm rounded-md hover:bg-red-600 focus:outline-none mx-1"
                            @click="deleteCategory(category)"
                        >
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
