<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Swal from "sweetalert2";
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    menus: Array,
    categories: Array,
});

const showCreateForm = ref(false);

const toggleCreateForm = () => {
    showCreateForm.value = !showCreateForm.value;
};

const form = useForm({
    image_path: null,
    menu_name: "",
    category_id: "",
    description: "",
    price: "",
    availability: "",
    errors: {},
});

const submitForm = () => {
    const formData = new FormData();
    if (form.image_path) {
        formData.append("image_path", form.image_path);
    }
    formData.append("menu_name", form.menu_name);
    formData.append("category_id", form.category_id);
    formData.append("description", form.description);
    formData.append("price", form.price);
    formData.append("availability", form.availability);

    form.post("/admin/menus/store", {
        body: formData,
        onSuccess: () => {
            Swal.fire({
                title: "Success",
                text: "Menu successfully added.",
                icon: "success",
                confirmButtonText: "Close",
            });
            form.reset();
            showCreateForm.value = false;
        },
        onError: () => {
            Swal.fire({
                title: "Error",
                text: form.errors ? Object.values(form.errors).join(", ") : "Unexpected error.",
                icon: "error",
                confirmButtonText: "Close",
            });
        },
    });
};

const showEditForm = ref(false);
const selectedMenu = ref(null);

const closeEdit = () => {
    showEditForm.value = !showEditForm.value;
};

const editMenu = (menu) => {
    selectedMenu.value = { ...menu };
    form.image_path = null;
    form.menu_name = selectedMenu.value.menu_name;
    form.category_id = selectedMenu.value.category_id;
    form.description = selectedMenu.value.description;
    form.price = selectedMenu.value.price;
    form.availability = selectedMenu.value.availability;
    showEditForm.value = true;
};

const submitEdit = () => {
    const formData = new FormData();
    if (form.image_path) {
        formData.append("image_path", form.image_path);
    }
    formData.append("menu_name", form.menu_name);
    formData.append("category_id", form.category_id);
    formData.append("description", form.description);
    formData.append("price", form.price);
    formData.append("availability", form.availability);
    formData.append("_method", 'PUT');

    router.post(`/admin/menus/update/${selectedMenu.value.id}`, formData, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: "Success",
                text: "Menu successfully updated.",
                icon: "success",
                confirmButtonText: "Close",
            }).then(() => {
                showEditForm.value = false;
                selectedMenu.value = null;
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                title: "Error",
                text: "Failed to update Menu. Please check the input fields.",
                icon: "error",
                confirmButtonText: "Close",
            });
        },
    });
};

const deleteMenu = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            // Use the Inertia.js delete method
            router.delete(`/admin/menus/delete/${id}`, {
                onSuccess: () => {
                    Swal.fire("Deleted!", "Menu has been deleted.", "success");
                },
                onError: () => {
                    Swal.fire("Error!", "An error occurred while deleting the menu.", "error");
                },
            });
        }
    });
};
</script>

<template>
    <AdminLayout>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center"><i class="fa-solid fa-tags"></i> Menus</li>
            </ol>
        </nav>

        <div class="mt-4">
            <button @click="toggleCreateForm" class="px-4 py-2 mb-2 bg-[#306679] hover:bg-[#255065] text-white rounded">
                <i class="fa-solid fa-plus"></i> Create Menu
            </button>
        </div>

        <!-- Create Form -->
        <div v-if="showCreateForm"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
            @click.self="toggleCreateForm">
            <div class="bg-white p-4 rounded-md shadow-md w-1/3">
                <h2 class="text-lg font-semibold mb-4">Create Menu</h2>
                <form @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label for="image_path" class="block text-sm font-medium text-gray-700">
                            Image
                        </label>
                        <input type="file" id="image_path" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            @change="(e) =>
                                (form.image_path = e.target.files.length ? e.target.files[0] : null)
                                " />
                    </div>

                    <div class="mb-4">
                        <label for="menu_name" class="block text-sm font-medium text-gray-700">
                            Menu Name
                        </label>
                        <input type="text" id="menu_name" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.menu_name" />
                        <div v-if="form.errors.menu_name" class="text-red-500 text-sm">
                            {{ form.errors.menu_name }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">
                            Category
                        </label>
                        <select id="category_id" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.category_id">
                            <option value="" disabled>Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.category_name }}
                            </option>
                        </select>
                        <div v-if="form.errors.category_id" class="text-red-500 text-sm">
                            {{ form.errors.category_id }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea id="description" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.description" rows="4" placeholder="Enter description here"></textarea>
                        <div v-if="form.errors.description" class="text-red-500 text-sm">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">
                            Price
                        </label>
                        <input type="number" id="price" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.price" />
                        <div v-if="form.errors.price" class="text-red-500 text-sm">
                            {{ form.errors.price }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="availability" class="block text-sm font-medium text-gray-700">
                            Availability
                        </label>
                        <select class="mt-1 p-2 w-full border border-gray-300 rounded-md" id="availability"
                            v-model="form.availability">
                            <option value="" disabled>Select Availability</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        <div v-if="form.errors.availability" class="text-red-500 text-sm">
                            {{ form.errors.availability }}
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded mr-2"
                            @click="toggleCreateForm">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#306679] hover:bg-[#255065] text-white rounded">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Form -->
        <div v-if="showEditForm"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
            @click.self="closeEdit">
            <div class="bg-white p-4 rounded-md shadow-md w-1/3">
                <h2 class="text-lg font-semibold mb-4">Edit Menu</h2>
                <form @submit.prevent="submitEdit">
                    <div class="mb-4">

                        <input
                            type="file"
                            id="edit_image_path"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            @change="(e) => (form.image_path = e.target.files.length ? e.target.files[0] : null)"
                        />
                    </div>

                    <div class="mb-4">
                        <label for="menu_name" class="block text-sm font-medium text-gray-700">
                            Menu Name
                        </label>
                        <input type="text" id="menu_name" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.menu_name" />
                        <div v-if="form.errors.menu_name" class="text-red-500 text-sm">
                            {{ form.errors.menu_name }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">
                            Category
                        </label>
                        <select id="category_id" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.category_id">
                            <option value="" disabled>Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.category_name }}
                            </option>
                        </select>
                        <div v-if="form.errors.category_id" class="text-red-500 text-sm">
                            {{ form.errors.category_id }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea id="description" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.description" rows="4" placeholder="Enter description here"></textarea>
                        <div v-if="form.errors.description" class="text-red-500 text-sm">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">
                            Price
                        </label>
                        <input type="number" id="price" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.price" />
                        <div v-if="form.errors.price" class="text-red-500 text-sm">
                            {{ form.errors.price }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="availability" class="block text-sm font-medium text-gray-700">
                            Availability
                        </label>
                        <select class="mt-1 p-2 w-full border border-gray-300 rounded-md" id="availability"
                            v-model="form.availability">
                            <option value="" disabled>Select Availability</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        <div v-if="form.errors.availability" class="text-red-500 text-sm">
                            {{ form.errors.availability }}
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded mr-2" @click="closeEdit">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#306679] hover:bg-[#255065] text-white rounded">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <div v-if="menus.length === 0" class="text-center p-4">
                <p class="text-gray-600">No Menus found. <span>Create a new menu</span>.</p>
            </div>

            <table v-else class="w-full text-sm text-left text-black bg-white">
                <thead class="text-xs text-black uppercase bg-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">#</th>
                        <th scope="col" class="px-6 py-3 text-center">Image</th>
                        <th scope="col" class="px-6 py-3 text-center">Menu Name</th>
                        <th scope="col" class="px-6 py-3 text-center">Category</th>
                        <th scope="col" class="px-6 py-3 text-center">Description</th>
                        <th scope="col" class="px-6 py-3 text-center">Price</th>
                        <th scope="col" class="px-6 py-3 text-center">Availability</th>
                        <th scope="col" class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(menu, index) in menus" :key="menu.id" class="bg-white border-b">
                        <th class="text-center">{{ index + 1 }}</th>
                        <td class="px-6 py-4 text-center">
                            <img v-if="menu.image_path" :src="`/storage/${menu.image_path}`" alt="Menu Image"
                                class="w-16 h-16 object-cover mx-auto" />
                            <span v-else class="text-gray-400">No image</span>
                        </td>
                        <td class="px-6 py-4 text-center">{{ menu.menu_name }}</td>
                        <td class="px-6 py-4 text-center">{{ menu.category?.category_name || 'No category' }}</td>
                        <td class="px-6 py-4 text-center">{{ menu.description }}</td>
                        <td class="px-6 py-4 text-center">₱{{ menu.price }}</td>
                        <td class="px-6 py-4 text-center"
                            :class="menu.availability === 'available' ? 'text-green-500' : 'text-red-500'">
                            {{ menu.availability }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                class="bg-yellow-500 text-white px-4 py-2 text-sm rounded-md hover:bg-yellow-600 focus:outline-none mx-1"
                                @click="editMenu(menu)">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button
                                class="bg-red-500 text-white px-4 py-2 text-sm rounded-md hover:bg-red-600 focus:outline-none mx-1"
                                @click="deleteMenu(menu.id)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
