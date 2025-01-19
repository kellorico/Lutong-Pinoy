<script setup>
import Container from "../../../Components/Container.vue";
import Title from "../../../Components/Title.vue";
import InputField from "../../../Components/InputField.vue";
import PrimaryButton from "../../../Components/PrimaryButton.vue";
import ErrorMessages from "../../../Components/ErrorMessages.vue";
import {  useForm } from "@inertiajs/vue3";

defineProps({
    status: String,
})

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
});

const submit = () => {
    form.put(route('profile.password'),{
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => form.reset()
    });
};
</script>

<template>
    <Container class="mb-6">
        <div class="mb-6">
            <Title>Update Password</Title>
            <p>Ensure you are using a long, random passwords to stay secure.</p>

            <ErrorMessages :errors="form.errors" />

            <form
                @submit.prevent="submit"
                class="space-y-6"
            >
                <InputField
                    type="password"
                    label="Current Password"
                    icon="id-badge"
                    class="w-1/2"
                    v-model="form.current_password"
                />

                <InputField
                    type="password"
                    label="Password"
                    icon="id-badge"
                    class="w-1/2"
                    v-model="form.password"
                />

                <InputField
                    type="password"
                    label="Confirm Password"
                    icon="id-badge"
                    class="w-1/2"
                    v-model="form.password_confirmation"
                />
                <p v-if="form.recentlySuccessful" class="text-green-500 font-medium">Saved!</p>
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
            </form>
        </div>
    </Container>
</template>
