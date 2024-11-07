<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const formRegister = useForm({
    name: "",
    umur: "",
    username: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const formLogin = useForm({
    username: "",
    password: "",
    remember: false,
});

function submit(method) {
    $(".hide-on-process").hide();
    if (method === "login") {
        toastr.warning("Sedang diproses, mohon tunggu!", "Warning!");
        setTimeout(function () {
            formLogin.post(route("login"), {
                onSuccess: function () {
                    toastr.success("Berhasil masuk!", "Success");
                    formLogin.reset("password");
                },
                onError: function (callback) {
                    toastr.error("Gagal masuk!", "Error");
                },
            });
        }, 3000);
    } else if (method === "register") {
        toastr.warning("Sedang diproses, mohon tunggu!", "Warning!");
        setTimeout(function () {
            formRegister.post(route("register"), {
                onSuccess: function () {
                    toastr.success("Berhasil mendaftar!", "Success");
                    formRegister.reset("password", "password_confirmation");
                },
                onError: function (callback) {
                    const { username, email, password } = callback;
                    const tmpCallback = Object.values(callback);
                    if (tmpCallback.includes("validation.unique")) {
                        const txt = (username ? "Username sudah tidak dapat digunakan." : (email ? "Email sudah tidak dapat digunakan." : ""));
                        toastr.error(`Gagal mendaftar!\n${txt}`, "Error");
                    } else if (tmpCallback.includes("validation.min.string")) {
                        const txt = (username ? "Username harus lebih dari 4 huruf." : (password ? "Password harus lebih dari 4 huruf." : ""));
                        toastr.error(`Gagal mendaftar!\n${txt}`, "Error");
                    }
                },
            });
        }, 3000);
    }
    setTimeout(function () {
        $(".hide-on-process").show();
    }, 7000);
}

function changeSection(section) {
    if (section === "register") {
        $("#login").hide();
        $("#register").show();
    } else if (section === "login") {
        $("#register").hide();
        $("#login").show();
    }
}

$(document).ready(function () {
    (function () {
        $("#login").hide();
    })();
});
</script>

<template>
    <Head title="Welcome" />
    <div class="w-full flex flex-wrap justify-center">
        <div
            id="modal"
            class="shadow-sm md:shadow-lg lg:shadow-2xl p-4 bg-white rounded"
        >
            <div id="register">
                <h1 class="text-center uppercase font-medium text-gray-700">
                    register
                </h1>
                <form @submit.prevent="submit('register')">
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formRegister.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                    </div>

                    <div>
                        <InputLabel for="umur" value="Umur" />

                        <TextInput
                            id="umur"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="formRegister.umur"
                            required
                            autofocus
                            autocomplete="umur"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="username" value="Username" />

                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formRegister.username"
                            required
                            autocomplete="username"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="formRegister.email"
                            required
                            autocomplete="email"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="formRegister.password"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="password_confirmation"
                            value="Confirm Password"
                        />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="formRegister.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <span
                            @click="changeSection('login')"
                            class="cursor-pointer underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Already registered?
                        </span>

                        <PrimaryButton
                            class="hide-on-process ms-4"
                            :class="{
                                'opacity-25': formRegister.processing,
                            }"
                            :disabled="formRegister.processing"
                        >
                            Register
                        </PrimaryButton>
                    </div>
                </form>
            </div>
            <div id="login">
                <h1 class="text-center uppercase font-medium text-gray-700">
                    login
                </h1>
                <form @submit.prevent="submit('login')">
                    <div>
                        <InputLabel for="username" value="Username" />

                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formLogin.username"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="formLogin.password"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    <div class="block mt-4">
                        <label class="flex items-center">
                            <Checkbox
                                name="remember"
                                v-model:checked="formLogin.remember"
                            />
                            <span class="ms-2 text-sm text-gray-600"
                                >Remember me</span
                            >
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <div class="flex gap-4">
                            <span
                                @click="changeSection('register')"
                                class="cursor-pointer underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Register new account
                            </span>
                        </div>

                        <PrimaryButton
                            class="hide-on-process ms-4"
                            :class="{ 'opacity-25': formLogin.processing }"
                            :disabled="formLogin.processing"
                        >
                            Log in
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
