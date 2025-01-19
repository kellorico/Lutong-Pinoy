<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { useForm } from '@inertiajs/vue3';
import ErrorMessages from '@/Components/ErrorMessages.vue';

const form = useForm({
    username: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const submit = () => {
    form.post(route('register'),{
        onFinish: () => form.reset('password','password_confirmation')
    })
}
</script>

<template>
    <UserLayout>
        <Head title="Register"/>
        <div class="flex items-center justify-center bg-gray-50 mt-20">
  <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 text-center">Register</h2>
    <ErrorMessages :errors="form.errors"/>
    <form @submit.prevent="submit" class="mt-6 space-y-4">
      <!-- Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-600">Full Name</label>
        <input
          v-model="form.username"
          type="text"
          id="name"
          placeholder="Enter your full name"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-orange-200"
        />
      </div>
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
      <!-- Confirm Password -->
      <div>
        <label for="confirm-password" class="block text-sm font-medium text-gray-600">Confirm Password</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          id="confirm-password"
          placeholder="Confirm your password"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-orange-200"
        />
      </div>
      <!-- Submit Button -->
      <button
        type="submit"
        class="w-full bg-orange-500 text-white font-medium py-2 rounded-lg hover:bg-orange-600 focus:outline-none"
      >
        Register
      </button>
      <!-- Already have an account -->
      <p class="text-sm text-gray-600 text-center mt-4">
        Already have an account?
        <Link :href="route('login')" class="text-orange-500 font-medium hover:underline">Log In</Link>
      </p>
    </form>
  </div>
</div>
    </UserLayout>
</template>
