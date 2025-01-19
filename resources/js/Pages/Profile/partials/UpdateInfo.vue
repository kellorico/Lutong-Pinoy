<script setup>
import Container from "../../../Components/Container.vue";
import Title from "../../../Components/Title.vue";
import InputField from "../../../Components/InputField.vue";
import PrimaryButton from "../../../Components/PrimaryButton.vue";
import ErrorMessages from "../../../Components/ErrorMessages.vue";
import SessionMessages from "@/Components/SessionMessages.vue";
import { router, useForm } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    status: String,
});

const form = useForm({
    username: props.user.username,
    email: props.user.email,
    address: props.user.address,
    phone: props.user.phone,
});

</script>

<template>
    <Container class="mb-6 mt-20">
        <div class="mb-6">
            <Title>Update Information</Title>
            <p>Update your account's profile information and email address.</p>

            <ErrorMessages :errors="form.errors" />

                    <SessionMessages v-if="form.recentlySuccessful" :status="status" />

            <form
                @submit.prevent="form.patch(route('profile.info'))"
                class="space-y-6"
            >
                <InputField
                    label="User Name"
                    icon="id-badge"
                    class="w-1/2"
                    v-model="form.username"
                />

                <InputField
                    label="Email"
                    icon="at"
                    class="w-1/2"
                    v-model="form.email"
                />

                <InputField
                    label="Address"
                    icon="map-marker-alt"
                    class="w-1/2"
                    v-model="form.address"
                />

                <InputField
                    label="Phone"
                    icon="phone"
                    class="w-1/2"
                    v-model="form.phone"
                />

                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
            </form>
        </div>
    </Container>
</template>
