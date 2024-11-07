<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import ModifyInput from "@/Components/ModifyInput.vue";
import { Head } from "@inertiajs/vue3";

function editData($id, $user) {
    const newData = {
        name: $user.name,
        username: $user.username,
        umur: $user.umur,
        status_user: $user.status_user,
        email: $user.email,
        password: $user.password,
        password_confirmation: $user.password,
    };

    toastr.warning("Sedang diproses, mohon tunggu!", "Perhatian!");
    $.ajax({
        url: `http://localhost:8000/api/users/${$id}`,
        type: "PUT",
        data: newData,
        success: function (callback) {
            console.log(callback);
            const { status } = callback;
            if (status == 201) {
                toastr.success("Berhasil menyimpan data!", "Sukses!");
                location.reload();
            } else {
                toastr.error("Gagal menyimpan data!", "Kesalahan!");
            }
        },
        error: function (err) {
            console.error("Error updating data:", err);
            toastr.error("Gagal menyimpan data!", "Kesalahan!");
        },
    });
}

function deleteData($id) {
    toastr.warning("Sedang diproses, mohon tunggu!", "Perhatian!");
    $.ajax({
        url: `http://localhost:8000/api/users/${$id}`,
        type: "DELETE",
        success: function (callback) {
            console.log(callback);
            const { status } = callback;
            if (status == 201) {
                toastr.success("Berhasil menghapus data!", "Sukses!");
                location.reload();
            } else {
                toastr.error("Gagal menghapus data!", "Kesalahan!");
            }
        },
        error: function (err) {
            console.error("Error updating data:", err);
            toastr.error("Gagal menghapus data!", "Kesalahan!");
        },
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="w-full flex">
                <div class="container mx-auto">
                    <div class="flex justify-between">
                        <h1 class="text-2xl font-bold mb-6">Users</h1>
                        <div class="flex">
                            <ModifyInput
                                ref="searchParams"
                                model="auto_tags"
                                :isAuto="true"
                                :tmpDatas="datasUsers"
                            ></ModifyInput>
                            <button
                                @click="getSearch"
                                class="bg-blue-500 text-white py-2 px-4 rounded mt-2"
                            >
                                cari
                            </button>
                        </div>
                    </div>

                    <table class="min-w-full bg-white rounded-lg shadow-md">
                        <thead>
                            <tr
                                class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal"
                            >
                                <th v-for="head in headColoumn" :key="head.key" @click="sort(head.key)" class="py-3 px-6 text-center">{{ head.label }}</th>
                                <th class="py-3 px-6 text-center">Username</th>
                                <th class="py-3 px-6 text-center">Password</th>
                                <th class="py-3 px-6 text-center">Email</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            id="datas-load-content"
                            class="text-gray-700 text-sm font-light"
                        >
                            <tr v-if="!this.fxdUsers.length">
                                <td colspan="5" class="text-center">
                                    Tidak ada data users.
                                </td>
                            </tr>
                            <tr
                                v-else
                                v-for="(user, index) in fxdUsers"
                                :key="user.id"
                                class="border-b border-gray-200 odd:bg-gray-50 even:bg-gray-200"
                            >
                                <td class="text-center">{{ index + 1 }}</td>
                                <td class="text-center">
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="user.name"
                                        required
                                        autofocus
                                        autocomplete="name"
                                    />
                                </td>
                                <td>
                                    <TextInput
                                    id="umur"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="user.umur"
                                    required
                                    autofocus
                                    autocomplete="umur"
                                    model="set_date"
                                    />
                                </td>
                                <td class="flex m-4">
                                    <input
                                        type="radio"
                                        :name="'status_user_' + user.id"
                                        v-model="user.status_user"
                                        value="1"
                                        :checked="user.status_user == 1"
                                    /> <span>Aktif</span>

                                    <input
                                        type="radio"
                                        :name="'status_user_' + user.id"
                                        v-model="user.status_user"
                                        value="2"
                                        :checked="user.status_user == 2"
                                    /> <span>Tidak</span>
                                </td>
                                <td class="text-center">
                                    <TextInput
                                        id="username"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="user.username"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />
                                </td>
                                <td class="text-center">
                                    <TextInput
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        v-model="user.password"
                                        required
                                        autocomplete="new-password"
                                    />
                                </td>
                                <td class="text-center">
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="user.email"
                                        required
                                        autofocus
                                        autocomplete="email"
                                    />
                                </td>
                                <td class="text-center">
                                    <button
                                        @click="editData(user.id, user)"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded"
                                    >
                                        edit
                                    </button>
                                    <button
                                        @click="deleteData(user.id)"
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded"
                                    >
                                        delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            fxdUsers: [],
            datasUsers: [],
            headColoumn: [
                { key: 'norut', label: 'No' },
                { key: 'name', label: 'Name' },
                { key: 'umur', label: 'Umur' },
                { key: 'status', label: 'Status' }
            ],
        };
    },
    methods: {
        async fetchUsers(params = null) {
            try {
                let tmpUsers = [];
                toastr.warning("Sedang diproses, mohon tunggu!", "Perhatian!");
                await axios
                    .get("http://localhost:8000/api/users")
                    .then(function (callback) {
                        console.log(callback);
                        const { datas } = callback.data;
                        tmpUsers = datas;
                        toastr.success("Berhasil mengambil data!", "Sukses!");
                    })
                    .catch(function (callback) {
                        console.log(callback);
                        const { message, response } = callback;
                        console.error("Error fetching users:", message);
                        console.error(
                            "Error fetching users:",
                            response.data.message
                        );
                        toastr.error("Gagal mengambil data!", "Kesalahan!");
                    });
                this.datasUsers = tmpUsers;
                this.fxdUsers = tmpUsers;
            } catch (error) {
                console.error("Error fetching users:", error);
                toastr.error("Gagal mengambil data!", "Kesalahan!");
            }
        },
        async getSearch() {
            let isValidData = this.fxdUsers.filter((list) => ((list.name).toLowerCase()).includes(this.$refs.searchParams.$refs.modif.$refs.input.value.toLowerCase()));
            if (!this.$refs.searchParams.$refs.modif.$refs.input.value) {
                await this.fetchUsers();
            } else if (!isValidData.length) {
                await this.fetchUsers();
                this.fxdUsers = this.fxdUsers.filter((list) => ((list.name).toLowerCase()).includes(this.$refs.searchParams.$refs.modif.$refs.input.value.toLowerCase()));
            } else {
                this.fxdUsers = isValidData;
            }
        },
        onFilterSort(sort) {
        if (this.sortBy === columnKey) {
            this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortBy = columnKey;
            this.sortOrder = 'asc';
        }
        this.fetchData();
        }
    },
    async mounted() {
        await this.fetchUsers();
    },
};
</script>
