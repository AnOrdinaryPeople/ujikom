<template>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <markdown class="mb-body" :content="txtt" />
                </div>
                <div class="col-6">
                    <markdown class="mb-body" :content="txt" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        sauce: process.env.MIX_APP_URL,
        txt: "",
        txtt: ""
    }),
    created() {
        this.txt = `\`\`\`php
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => '${this.sauce}/api/user',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_POSTFIELDS => http_build_query([
        'limit' => 15,
        'skip' => 2,
        'sort_by' => 'name',
        'sort' => 'desc'
    ]),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer SECRET_TOKEN',
        'Access: ACCESS_TOKEN'
    ]
]);
$resp = curl_exec($curl);
$json = json_decode($resp);
curl_close($curl);

echo print_r($json);
\`\`\``;
        this.txtt = `# Penjelasan
1. Buat variabel untuk menampung \`curl_init()\`.
1. Selanjutnya mengatur cURL dengan \`curl_setopt_array()\`.
   1. Untuk parameter pertama simpan variabel yang menampung \`curl_init()\`.
   1. Parameter kedua untuk *option* cURLnya.
      1. \`CURLOPT_URL\` untuk menentukan URL yang dituju.
      1. \`CURLOPT_RETURNTRANSFER\` untuk menerima data setelah request.
      1. \`CURLOPT_ENCODING\` yang ini dilewat dulu.
      1. \`CURLOPT_MAXREDIRS\` jumlah maksimum *redirect*.
      1. \`CURLOPT_TIMEOUT\` waktu maksimum untuk melakukan request. Waktu yang digunakan "detik".
      1. \`CURLOPT_HTTP_VERSION\` yang ini dilewat juga.
      1. \`CURLOPT_CUSTOMREQUEST\` mirip seperti \`<form method="post">\`.
      1. \`CURLOPT_POSTFIELDS\` data yang akan kirim ke URL yang dituju.
      1. \`CURLOPT_HTTPHEADER\` untuk mengakses ke URL yang dituju.
      1. Untuk Informasi yang lebih lengkap, [klik disini](https://www.php.net/manual/en/function.curl-setopt.php).
1. Berikutnya jalankan cURLnya dengan \`curl_exec()\`.
1. Setelah menerima datanya, decode JSON dengan \`json_decode()\`.
1. Setelah dijalankan tutup cURL dengan \`curl_close()\`.
1. Tampilkan data.`;
    }
};
</script>
