<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { useForm } from '@inertiajs/vue3';
import ErrorMessages from "@/Components/ErrorMessages.vue";

const form = useForm({
    email: '',
    password: ''
})

const submit = () => {
    form.post(route('login'))
}
</script>

<template>
  <UserLayout>
      <Head title="Login"/>
    <div class="flex items-center justify-center bg-gray-50 mt-20">
      <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center">Login</h2>
          <ErrorMessages :errors="form.errors"/>
        <form @submit.prevent="submit" class="mt-6 space-y-4">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
            <input
              v-model="form.email"
              type="email"
              id="email"
              placeholder="Enter your email"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-orange-200"
            />
          </div>
          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
            <input
              v-model="form.password"
              type="password"
              id="password"
              placeholder="Enter your password"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-orange-200"
            />
          </div>
          <!-- Submit Button -->
          <button
            type="submit"
            class="w-full bg-orange-500 text-white font-medium py-2 rounded-lg hover:bg-orange-600 focus:outline-none"
          >
            Log In
          </button>
          <!-- Already have an account -->
          <p class="text-sm text-gray-600 text-center mt-4">
            Don't have an account?
            <Link :href="route('register')" class="text-orange-500 font-medium hover:underline">Register</Link>
          </p>
        </form>
      </div>
    </div>
  </UserLayout>
</template>
