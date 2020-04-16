<template>
    <div class="container mb-4">
        <h1 class="text-center border-bottom p-2">Getting Started</h1>
        <div class="border-bottom pt-4">
            <h2 id="step-first">1. Login Dulu</h2>
            <p>
                Yang paling utama dan benar-benar utama yaitu
                <router-link to="/login">login</router-link>, kalau belum punya akun tinggal
                <router-link to="/register">daftar</router-link>. Simpel.
            </p>
        </div>
        <div class="border-bottom pt-4">
            <h2 id="step-second">2. Buat Token</h2>
            <p>
                Yang kedua yaitu membuat Access Token dan Secret Token. Ada dihalaman
                <router-link to="/developer/dashboard">Dashboard</router-link>
                {{ ' lalu klik' }}
                <strong>Generate Token</strong>.
                <br />
                <br />
                <strong>PENTING!</strong>
                <br />Jangan membagikan kedua token ini kepada siapapun!
            </p>
        </div>
        <div class="border-bottom pt-4">
            <h2 id="step-third">3. Buat Request</h2>
            <div class="p-4">
                <markdown class="mb-body" :content="txt" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        sauce: process.env.MIX_APP_URL,
        txt: ""
    }),
    mounted() {
        this.txt = `### Buka Aplikasi Postman
Perlu dijelaskan? kayaknya tidak :p

### Login yang Kedua Yaitu di Postman
Login lagi? tinggal login, gak punya akun di Postman? tinggal daftar. Simpel (2)

### Masukkan Token
Klik **Authorization**. Lalu **TYPE**nya pilih \`Bearer Token\`, isi tokennya dengan Secret Token kamu.

Klik **Headers**. Buat key baru dengan nama \`Access\`, valuenya ketik Access Token yang kamu miliki.

### Request
Klik **Enter request URL**. Untuk percobaan ketik \`${this.sauce}/api/user\``;
    }
};
</script>
