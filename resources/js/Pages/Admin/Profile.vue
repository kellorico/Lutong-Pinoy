<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
</script>

<template>
  <AdminLayout>
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-8 mt-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Profile Settings</h2>
      <form>
        <!-- Username -->
        <div class="mb-6">
          <label for="username" class="block text-gray-700 mb-2">Username</label>
          <input
            type="text"
            id="username"
            placeholder="Enter your username"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Email -->
        <div class="mb-6">
          <label for="email" class="block text-gray-700 mb-2">Email</label>
          <input
            type="email"
            id="email"
            placeholder="Enter your email"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Password -->
        <div class="mb-6">
          <label for="password" class="block text-gray-700 mb-2">New Password</label>
          <input
            type="password"
            id="password"
            placeholder="Enter a new password"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
          <label for="password_confirmation" class="block text-gray-700 mb-2">Confirm Password</label>
          <input
            type="password"
            id="password_confirmation"
            placeholder="Confirm your new password"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full bg-[#306679] text-white p-3 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Save Changes
        </button>
      </form>
    </div>
  </AdminLayout>
</template>
