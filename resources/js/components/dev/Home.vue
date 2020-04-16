<template>
    <div class="container">
        <h1 class="text-center border-bottom p-2">Belajar API Disini</h1>
        <p>API disini tidak terlalu rumit. Data yang akan kamu terima berupa JSON.</p>
        <div v-for="(i, key) in text" :key="key" class="pt-4 border-bottom">
            <h4>{{ i.title }}</h4>
            <p>
                {{ i.p }}
                <br />
                <a :href="i.sauce" target="_blank">Sauce</a>
                {{ i.download ? ' | ' : '' }}
                <a
                    v-if="i.download"
                    :href="i.download"
                    target="_blank"
                >Download</a>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        text: [
            {
                title: "Apa itu API?",
                p: `API adalah singkatan dari Application Programming Interface, dan memungkinkan developer untuk mengintegrasikan dua bagian dari aplikasi atau dengan aplikasi yang berbeda secara bersamaan. API terdiri dari berbagai elemen seperti function, protocols, dan tools lainnya yang memungkinkan developers untuk membuat aplikasi. Tujuan penggunaan API adalah untuk mempercepat proses development dengan menyediakan function secara terpisah sehingga developer tidak perlu membuat fitur yang serupa. Penerapan API akan sangat terasa jika fitur yang diinginkan sudah sangat kompleks, tentu membutuhkan waktu untuk membuat yang serupa dengannya. Misalnya: integrasi dengan payment gateway. Terdapat berbagai jenis sistem API yang dapat digunakan, termasuk sistem operasi, library, dan web.`,
                sauce:
                    "https://www.codepolitan.com/mengenal-apa-itu-web-api-5a0c2855799c8"
            },
            {
                title: "Mengenal AJAX",
                p: `Asynchronous JavaScript and XMLHTTP, atau disingkat AJaX, adalah suatu teknik pemrograman berbasis web untuk menciptakan aplikasi web interaktif. Tujuannya adalah untuk memindahkan sebagian besar interaksi pada komputer web surfer, melakukan pertukaran data dengan server di belakang layar, sehingga halaman web tidak harus dibaca ulang secara keseluruhan setiap kali seorang pengguna melakukan perubahan. Hal ini akan meningkatkan interaktivitas, kecepatan, dan usability.`,
                sauce: "https://id.wikipedia.org/wiki/AJAX"
            },
            {
                title: "Menggunakan Postman",
                p: `POSTMAN adalah sebuah aplikasi (berupa plugin) untuk browser chrome, fungsinya adalah sebagai REST Client atau istilahnya adalah aplikasi yang digunakan untuk melakukan uji coba REST API yang telah kita buat.`,
                sauce:
                    "https://blog.pranawa.com/step-by-step-belajar-rest-api-menggunakan-flask-di-windows-instalasi-postman",
                download: "https://www.postman.com/downloads"
            }
        ]
    })
};
</script>
