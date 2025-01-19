<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
    users: Array
})

const showCreateForm = ref(false); // Controls visibility of the create user form
const showEditForm = ref(false); // Controls visibility of the edit user form
const role = ref("customer"); // Tracks the selected role for creating user
const isAdmin = ref(false); // Tracks if the selected role is admin for creating user
const selectedUser = ref(null); // Tracks the user being edited

// Toggle the create user form visibility
const toggleCreateForm = () => {
    showCreateForm.value = !showCreateForm.value;
};

// Toggle the edit user form visibility
const toggleEditForm = () => {
    showEditForm.value = !showEditForm.value;
};

// Handle role change event and update isAdmin for create form
const handleRoleChange = (event) => {
    role.value = event.target.value;
};

// Watch for role changes and update isAdmin for create form
watch(role, (newRole) => {
    isAdmin.value = newRole === "admin";
});

// Handle editing of user
const editUser = (user) => {
    selectedUser.value = { ...user }; // Clone the user data
    form.username = user.username;   // Set username to form
    form.email = user.email;
    form.address = user.address;
    form.phone = user.phone;         // Set email to form
    showEditForm.value = true;       // Show the form
};

const form = useForm({
    username: null,
    email: null,
    role: role.value, // Bind role from ref
    address: null,
    phone: null,
    password: null,
    password_confirmation: null
});

// Submit form for creating user
const submitCreate = () => {
    if (form.password !== form.password_confirmation) {
        Swal.fire({
            title: 'Error',
            text: 'Password and confirm password do not match.',
            icon: 'error',
            confirmButtonText: 'Close'
        });
        form.reset('password', 'password_confirmation');
        return;  // Ensure the form submission stops if there is a password mismatch
    }

    form.post('/admin/users/store', {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: 'Success',
                text: 'User successfully created',
                icon: 'success',
                confirmButtonText: 'Close'
            }).then(() => {
                showCreateForm.value = false;
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                title: 'Error',
                text: 'Failed to create user. Please check the input fields.',
                icon: 'error',
                confirmButtonText: 'Close'
            });
        }
    });
};

// Submit form for editing user
const submitEdit = () => {
    form.put(`/admin/users/update/${selectedUser.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: 'Success',
                text: 'User successfully updated',
                icon: 'success',
                confirmButtonText: 'Close'
            }).then(() => {
                showEditForm.value = false;
                selectedUser.value = null;
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                title: 'Error',
                text: 'Failed to update user. Please check the input fields.',
                icon: 'error',
                confirmButtonText: 'Close'
            });
        }
    });
};

// Handle deleting user
const deleteUser = (user) => {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete the user: ${user.username}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(`/admin/users/delete/${user.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success',
                        text: 'User successfully deleted.',
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
}

</script>

<template>
    <AdminLayout>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <i class="fa-solid fa-user"></i> Users
                </li>
            </ol>
        </nav>

        <!-- Create User Button -->
        <div class="mt-4">
            <button
                @click="toggleCreateForm"
                class="px-4 py-2 mb-2 bg-[#306679] hover:bg-[#255065] text-white rounded"
            >
                <i class="fa-solid fa-plus"></i> Create User
            </button>
        </div>

        <!-- Modal for Creating a User -->
        <div
            v-if="showCreateForm"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
        >
            <div class="bg-white p-4 rounded-md shadow-md w-1/3">
                <h2 class="text-lg font-semibold mb-4">Create User</h2>
                <form @submit.prevent="submitCreate">
                    <!-- Form fields here -->
                    <div class="mb-4">
                        <label
                            for="username"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Username
                        </label>
                        <input
                            type="text"
                            id="username"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.username"
                            :class="{'border-red-500': form.errors.username}"
                        />
                        <div v-if="form.errors.username" class="text-red-500 text-sm">
                            {{ form.errors.username }}
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-4">
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.email"
                            :class="{'border-red-500': form.errors.email}"
                        />
                        <div v-if="form.errors.email" class="text-red-500 text-sm">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input
                            type="text"
                            id="address"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.address"
                            :class="{'border-red-500': form.errors.address}"
                        />
                        <div v-if="form.errors.address" class="text-red-500 text-sm">
                            {{ form.errors.address }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input
                            type="text"
                            id="phone"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.phone"
                            :class="{'border-red-500': form.errors.phone}"
                        />
                        <div v-if="form.errors.phone" class="text-red-500 text-sm">
                            {{ form.errors.phone }}
                        </div>
                    </div>

                    <!-- Role Selector -->
                    <div class="mb-4">
                        <label
                            for="role"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Role
                        </label>
                        <select
                            id="role"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            @change="handleRoleChange"
                            v-model="form.role"
                            :class="{'border-red-500': form.errors.role}"
                        >
                            <option value="customer">Customer</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div v-if="form.errors.role" class="text-red-500 text-sm">
                            {{ form.errors.role }}
                        </div>
                    </div>

                    <!-- Admin Password Inputs -->
                    <div v-if="isAdmin" class="mb-4">
                        <label
                            for="password"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.password"
                            :class="{'border-red-500': form.errors.password}"
                        />
                        <div v-if="form.errors.password" class="text-red-500 text-sm">
                            {{ form.errors.password }}
                        </div>
                    </div>
                    <div v-if="isAdmin" class="mb-4">
                        <label
                            for="confirm_password"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            id="confirm_password"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.password_confirmation"
                            :class="{'border-red-500': form.errors.password_confirmation}"
                        />
                        <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm">
                            {{ form.errors.password_confirmation }}
                        </div>
                    </div>

                    <!-- Form Buttons -->
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

        <!-- Modal for Editing a User -->
        <div
            v-if="showEditForm"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
        >
            <div class="bg-white p-4 rounded-md shadow-md w-1/3">
                <h2 class="text-lg font-semibold mb-4">Edit User</h2>
                <form @submit.prevent="submitEdit">
                    <!-- Username Input -->
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input
                            type="text"
                            id="username"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.username"
                            :class="{'border-red-500': form.errors.username}"
                        />
                        <div v-if="form.errors.username" class="text-red-500 text-sm">
                            {{ form.errors.username }}
                        </div>
                    </div>
                    <!-- Email Input -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            type="email"
                            id="email"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.email"
                            :class="{'border-red-500': form.errors.email}"
                        />
                        <div v-if="form.errors.email" class="text-red-500 text-sm">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input
                            type="text"
                            id="address"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.address"
                            :class="{'border-red-500': form.errors.address}"
                        />
                        <div v-if="form.errors.address" class="text-red-500 text-sm">
                            {{ form.errors.address }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input
                            type="text"
                            id="phone"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                            v-model="form.phone"
                            :class="{'border-red-500': form.errors.phone}"
                        />
                        <div v-if="form.errors.phone" class="text-red-500 text-sm">
                            {{ form.errors.phone }}
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            class="px-4 py-2 bg-gray-500 text-white rounded mr-2"
                            @click="toggleEditForm"
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

        <!-- User Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <div v-if="users.length === 0" class="text-center p-4">
                <p class="text-gray-600">No users found. <span>Create a new user</span>.</p>
            </div>

            <table v-else class="w-full text-sm text-left text-black bg-white">
                <thead class="text-xs text-black uppercase bg-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">#</th>
                    <th scope="col" class="px-6 py-3 text-center">Name</th>
                    <th scope="col" class="px-6 py-3 text-center">Email</th>
                    <th scope="col" class="px-6 py-3 text-center">Address</th>
                    <th scope="col" class="px-6 py-3 text-center">Phone</th>
                    <th scope="col" class="px-6 py-3 text-center">Role</th>
                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, index) in users" :key="user.id" class="bg-white border-b">
                    <th class="text-center">{{ index + 1 }}</th>
                    <td class="px-6 py-4 text-center">{{ user.username }}</td>
                    <td class="px-6 py-4 text-center">{{ user.email }}</td>
                    <td class="px-6 py-4 text-center">{{ user.address }}</td>
                    <td class="px-6 py-4 text-center">{{ user.phone }}</td>
                    <td class="px-6 py-4 text-center">{{ user.role }}</td>
                    <td class="px-6 py-4 text-center">
                        <button
                            class="bg-yellow-500 text-white px-4 py-2 text-sm rounded-md hover:bg-yellow-600 focus:outline-none mx-1"
                            @click="editUser(user)"
                        >
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button
                            class="bg-red-500 text-white px-4 py-2 text-sm rounded-md hover:bg-red-600 focus:outline-none mx-1"
                            @click="deleteUser(user)"
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

